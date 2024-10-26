@extends('admin.layouts.app')

@section('content')
    <div class="container">
        <h1>Edit Peran</h1>

        <!-- Role Selection Dropdown -->
        <div class="form-group">
            <label for="role">Peran</label>
            <select name="role_id" id="role" class="form-control select2" required>
                <option value="">Pilih Peran</option>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Search Box -->
        <div class="form-group mt-3">
            <input type="text" id="searchPermissions" class="form-control" placeholder="Cari modul atau izin...">
        </div>


        <!-- Tabs for Modules -->
        <ul class="nav nav-tabs mt-3" id="permissionTabs">
            @foreach($groupedPermissions as $module => $modulePermissions)
                <li class="nav-item">
                    <a class="nav-link {{ $loop->first ? 'active' : '' }}" id="tab-{{ $module }}" data-bs-toggle="tab" href="#{{ $module }}">{{ Str::title(str_replace('-', ' ', $module)) }}</a>
                </li>
            @endforeach
        </ul>

        <!-- Permissions Content -->
        <form action="{{ route('roles-permissions.update', 0) }}" method="POST" id="permissionsForm">
            @csrf
            @method('PUT')

            <div class="tab-content mt-3" id="permissionsContent">
                @foreach($groupedPermissions as $module => $modulePermissions)
                    <div class="tab-pane fade show {{ $loop->first ? 'active' : '' }}" id="{{ $module }}">
                        <h4>{{ ucfirst($module) }}</h4>
                        <div class="form-group">
                            @foreach($modulePermissions as $permission)
                                <div class="form-check">
                                    <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" class="form-check-input permission-checkbox" id="permission-{{ $permission->id }}">
                                    <label class="form-check-label" for="permission-{{ $permission->id }}">{{ $permission->name }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <button type="submit" class="btn btn-primary mt-3">Update</button>
        </form>
    </div>

@endsection

@push('script-page')
    <script>
        // Search functionality for permissions
        document.getElementById('searchPermissions').addEventListener('keyup', function () {
            var filter = this.value.toLowerCase();
            var permissions = document.querySelectorAll('.form-check');
            var found = false;

            permissions.forEach(function (permission) {
                var text = permission.innerText.toLowerCase();
                if (text.includes(filter)) {
                    permission.style.display = '';
                    if (!found) {
                        // Find the parent tab and activate it
                        var tabPane = permission.closest('.tab-pane');
                        var tabId = tabPane.id;
                        var tabLink = document.querySelector(`#permissionTabs a[href="#${tabId}"]`);
                        var tabTrigger = new bootstrap.Tab(tabLink);
                        tabTrigger.show();
                        found = true;
                    }
                } else {
                    permission.style.display = 'none';
                }
            });
        });

        // Fetch permissions based on selected role
        function getQueryParam(name) {
            const urlParams = new URLSearchParams(window.location.search);
            return urlParams.get(name);
        }

        // Set the selected role from the URL parameter
        const roleIdFromUrl = getQueryParam('role_id');
        if (roleIdFromUrl) {
            document.getElementById('role').value = roleIdFromUrl;
            fetchPermissions(roleIdFromUrl);
        }

        // Event listener for role selection
        document.getElementById('role').addEventListener('change', function() {
            const roleId = this.value;
            if (roleId) {
                // Update the URL with the selected role ID
                const newUrl = new URL(window.location.href);
                newUrl.searchParams.set('role_id', roleId);
                window.history.pushState({}, '', newUrl);

                fetchPermissions(roleId);
            } else {
                // Reset checkboxes if no role is selected
                document.querySelectorAll('#permissionsContent input[type="checkbox"]').forEach(checkbox => {
                    checkbox.checked = false;
                });
            }
        });

        $('#role').select2({
            placeholder: "Pilih Peran",
            allowClear: true
        });

        // Event listener for role selection using Select2
        $('#role').on('change', function() {
            const roleId = $(this).val();
            if (roleId) {
                // Update the URL with the selected role ID
                const newUrl = new URL(window.location.href);
                newUrl.searchParams.set('role_id', roleId);
                window.history.pushState({}, '', newUrl);

                fetchPermissions(roleId);
            } else {
                // Reset checkboxes if no role is selected
                document.querySelectorAll('#permissionsContent input[type="checkbox"]').forEach(checkbox => {
                    checkbox.checked = false;
                });
            }
        });

        // Function to fetch permissions based on role ID
        function fetchPermissions(roleId) {
            fetch(`/api/roles/${roleId}/permissions`)
                .then(response => response.json())
                .then(data => {
                    document.querySelectorAll('#permissionsContent input[type="checkbox"]').forEach(checkbox => {
                        checkbox.checked = data.permissions.includes(checkbox.value);
                    });
                    // Update form action URL with selected role ID
                    document.getElementById('permissionsForm').action = `/roles-permissions/${roleId}`;
                    console.log('Form action updated to:', document.getElementById('permissionsForm').action); // Log the updated form action
                });
        }

        // Form submission validation
        document.getElementById('permissionsForm').addEventListener('submit', function(event) {
            const selectedRole = document.getElementById('role').value;
            if (!selectedRole) {
                event.preventDefault(); // Prevent form submission
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Pilih peran terlebih dahulu!',
                });
            }
        });

        // Initialize Bootstrap tabs
        var triggerTabList = [].slice.call(document.querySelectorAll('#permissionTabs a'))
        triggerTabList.forEach(function (triggerEl) {
            var tabTrigger = new bootstrap.Tab(triggerEl)

            triggerEl.addEventListener('click', function (event) {
                event.preventDefault()
                tabTrigger.show()
                localStorage.setItem('activeTab', triggerEl.getAttribute('href'));
            })
        });
        // Retrieve the active tab from local storage and activate it
        var activeTab = localStorage.getItem('activeTab');
        if (activeTab) {
            var tabTrigger = new bootstrap.Tab(document.querySelector(`#permissionTabs a[href="${activeTab}"]`));
            tabTrigger.show();
        }
    </script>
@endpush
