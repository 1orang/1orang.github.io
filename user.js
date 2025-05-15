document.querySelector(".add-btn").addEventListener("click", () => {
    Swal.fire({
        title: 'Tambah User',
        html: `
            <input id="swal-id" class="swal2-input" placeholder="id">
            <input id="swal-username" class="swal2-input" placeholder="username">
            <input id="swal-email" class="swal2-input" placeholder="email">
            <input id="swal-password" type="password" class="swal2-input" placeholder="Password">
        `,
        showCancelButton: true,
        confirmButtonText: 'Tambah',
        preConfirm: () => {
            const id = document.getElementById("swal-id").value;
            const username = document.getElementById("swal-usename").value;
            const email = document.getElementById("swal-email").value;
            const password = document.getElementById("swal-password").value;

            if (!id || !username || !email || !password) {
                Swal.showValidationMessage("Semua field harus diisi!");
                return false;
            }

            return fetch("tambah_user.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ id, username, email, password })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === "success") {
                    Swal.fire("Berhasil!", data.message, "success").then(() => location.reload());
                } else {
                    Swal.fire("Gagal!", data.message, "error");
                }
            })
            .catch(error => {
                console.error(error);
                Swal.fire("Error!", "Terjadi kesalahan saat mengirim data", "error");
            });
        }
    });
});




// Event listener untuk tombol edit user
Swal.fire({
    title: 'Edit User',
    html: `
        <input id="swal-id" class="swal2-input" value="${userId}" readonly>
        <input id="swal-username" class="swal2-input" value="${username}" placeholder="Username">
        <input id="swal-email" class="swal2-input" value="${email}" placeholder="Email">
        <input id="swal-password" type="password" class="swal2-input" placeholder="Password" value="${password}">
        <select id="swal-level" class="swal2-input">
            <option value="admin" ${level === 'admin' ? 'selected' : ''}>Admin</option>
            <option value="staff" ${level === 'staff' ? 'selected' : ''}>Staff</option>
            <option value="user" ${level === 'user' ? 'selected' : ''}>User</option>
        </select>
    `,
    showCancelButton: true,
    confirmButtonText: 'Simpan Perubahan',
    preConfirm: () => {
        const id = document.getElementById("swal-id").value;
        const username = document.getElementById("swal-username").value;
        const email = document.getElementById("swal-email").value;
        const password = document.getElementById("swal-password").value;
        const level = document.getElementById("swal-level").value;  // Ambil level

        // Validasi form: cek apakah semua field diisi
        if (!username || !email || !level) {
            Swal.showValidationMessage("Semua field harus diisi!");
            return false;
        }

        // Kirim data ke server menggunakan fetch
        return fetch("edit_user.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ id, username, email, password, level })  // Kirim juga level
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                Swal.fire("Berhasil!", data.message, "success").then(() => location.reload());
            } else {
                Swal.fire("Gagal!", data.message, "error");
            }
        })
        .catch(error => {
            console.error(error);
            Swal.fire("Error!", "Terjadi kesalahan saat mengirim data", "error");
        });
    }
});
