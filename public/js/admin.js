// Modal Functions
function openAddUserModal() {
    document.getElementById('addUserModal').classList.remove('hidden');
}

function closeAddUserModal() {
    document.getElementById('addUserModal').classList.add('hidden');
}

function openManageUsersModal() {
    document.getElementById('manageUsersModal').classList.remove('hidden');
}

function closeManageUsersModal() {
    document.getElementById('manageUsersModal').classList.add('hidden');
}

function openManageClassesModal() {
    document.getElementById('manageClassesModal').classList.remove('hidden');
}

function closeManageClassesModal() {
    document.getElementById('manageClassesModal').classList.add('hidden');
}

function openMonitoringModal() {
    document.getElementById('monitoringModal').classList.remove('hidden');
}

function closeMonitoringModal() {
    document.getElementById('monitoringModal').classList.add('hidden');
}

function openLogoutModal() {
    document.getElementById('logoutModal').classList.remove('hidden');
}

function closeLogoutModal() {
    document.getElementById('logoutModal').classList.add('hidden');
}

// Close modals on ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeAddUserModal();
        closeManageUsersModal();
        closeManageClassesModal();
        closeMonitoringModal();
        closeLogoutModal();
    }
});

// Close modals on backdrop click
document.addEventListener('click', function(e) {
    if (e.target.id === 'addUserModal') closeAddUserModal();
    if (e.target.id === 'manageUsersModal') closeManageUsersModal();
    if (e.target.id === 'manageClassesModal') closeManageClassesModal();
    if (e.target.id === 'monitoringModal') closeMonitoringModal();
    if (e.target.id === 'logoutModal') closeLogoutModal();
});

// Search Users Function
function filterUsers() {
    const searchInput = document.getElementById('searchUser');
    const filter = searchInput.value.toLowerCase();
    const userList = document.getElementById('userList');
    const userItems = userList.getElementsByClassName('user-item');
    
    for (let i = 0; i < userItems.length; i++) {
        const name = userItems[i].getAttribute('data-name');
        const email = userItems[i].getAttribute('data-email');
        const role = userItems[i].getAttribute('data-role');
        
        if (name.includes(filter) || email.includes(filter) || role.includes(filter)) {
            userItems[i].style.display = '';
        } else {
            userItems[i].style.display = 'none';
        }
    }
}

// View User Modal
function viewUser(id, nama, email, role) {
    document.getElementById('viewNama').textContent = nama;
    document.getElementById('viewEmail').textContent = email;
    document.getElementById('viewRole').textContent = role.charAt(0).toUpperCase() + role.slice(1);
    document.getElementById('viewUserModal').classList.remove('hidden');
}

function closeViewUserModal() {
    document.getElementById('viewUserModal').classList.add('hidden');
}

// Edit User Modal
function editUser(id, nama, email, role) {
    document.getElementById('editUserId').value = id;
    document.getElementById('editNama').value = nama;
    document.getElementById('editEmail').value = email;
    document.getElementById('editRole').value = role;
    
    const form = document.getElementById('editUserForm');
    form.action = '/admin/users/' + id;
    
    document.getElementById('editUserModal').classList.remove('hidden');
}

function closeEditUserModal() {
    document.getElementById('editUserModal').classList.add('hidden');
}

// View Class Detail Modal
function viewClassDetail(id, nama, token, guru, siswa) {
    document.getElementById('detailClassName').textContent = nama;
    document.getElementById('detailClassToken').textContent = token;
    document.getElementById('detailClassGuru').textContent = guru;
    document.getElementById('detailClassSiswa').textContent = siswa + ' siswa';
    document.getElementById('viewClassModal').classList.remove('hidden');
}

function closeViewClassModal() {
    document.getElementById('viewClassModal').classList.add('hidden');
}
