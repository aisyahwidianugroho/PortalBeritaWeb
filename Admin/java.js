// Variabel global
let currentUser = null;
let currentRole = null;
let currentDashboard = null;

// Data untuk dropdown kategori (sementara, nanti akan diambil dari database)
const categories = [
    { id: 1, nama_kategori: "News", articleCount: 0, status: "active" },
    { id: 2, nama_kategori: "Economy", articleCount: 0, status: "active" },
    { id: 3, nama_kategori: "Lifestyle", articleCount: 0, status: "active" },
    { id: 4, nama_kategori: "Culture", articleCount: 0, status: "active" },
    { id: 5, nama_kategori: "Sports", articleCount: 0, status: "active" },
    { id: 6, nama_kategori: "World", articleCount: 0, status: "active" },
    { id: 7, nama_kategori: "Fashion", articleCount: 0, status: "active" }
];

// Inisialisasi
document.addEventListener('DOMContentLoaded', function() {
    initializeLogin();
    initializeCategoryDropdown();
});

function initializeLogin() {
    const loginForm = document.getElementById('login-form');
    const roleOptions = document.querySelectorAll('.role-option');
    
    // Role selector
    roleOptions.forEach(option => {
        option.addEventListener('click', function() {
            roleOptions.forEach(opt => opt.classList.remove('active'));
            this.classList.add('active');
            currentRole = this.getAttribute('data-role');
        });
    });
    
    // Login form handler - YANG SUDAH DIPERBAIKI
    loginForm.addEventListener('submit', async function (e) {
    e.preventDefault();

    const fd = new FormData(loginForm);
    fd.append('role', currentRole || 'jurnalis'); // kirim role yang dipilih

    try {
        // auth.php melakukan session + redirect; tidak mengembalikan JSON
        const res = await fetch('Admin/auth.php', { method: 'POST', body: fd });

        // Kalau PHP melakukan redirect 302, fetch tidak otomatis berpindah halaman di UI.
        // Jadi kita pakai fallback: kalau res.ok, arahkan manual ke dashboard sesuai role.
        if (res.ok) {
        if (currentRole === 'jurnalis')      window.location.href = 'Jurnalis/dashboard.php';
        else if (currentRole === 'editor')   window.location.href = 'Editor/dashboard.php';
        else                                 window.location.href = 'Admin/dashboard.php';
        } else {
        alert('Login gagal. Periksa kembali kredensial.');
        }
    } catch (err) {
        console.error(err);
        alert('Terjadi kesalahan saat login.');
    }
    });

    // Set default role
    currentRole = 'jurnalis';
}

function initializeCategoryDropdown() {
    const categorySelect = document.getElementById('article-category');
    if (categorySelect) {
        categorySelect.innerHTML = '<option value="">Pilih Kategori</option>';
        categories.forEach(category => {
            const option = document.createElement('option');
            option.value = category.id;
            option.textContent = category.nama_kategori;
            categorySelect.appendChild(option);
        });
    }
}

function showDashboard(role, name) {
    document.getElementById('login-container').style.display = 'none';
    
    // Hide all dashboards
    document.getElementById('jurnalis-dashboard').style.display = 'none';
    document.getElementById('editor-dashboard').style.display = 'none';
    document.getElementById('admin-dashboard').style.display = 'none';
    
    // Show selected dashboard
    const dashboardId = role + '-dashboard';
    document.getElementById(dashboardId).style.display = 'flex';
    currentDashboard = role;
    
    // Update user info
    document.getElementById(role + '-username').textContent = name;
    
    // Initialize dashboard
    initializeDashboard(role);
}

function initializeDashboard(role) {
    // Setup menu items
    const menuItems = document.querySelectorAll('#' + role + '-dashboard .menu-item');
    menuItems.forEach(item => {
        item.addEventListener('click', function() {
            // Update active menu
            menuItems.forEach(i => i.classList.remove('active'));
            this.classList.add('active');
            
            // Show corresponding page
            const page = this.getAttribute('data-page');
            showPage(page);
        });
    });

    // Setup tabs
    const tabs = document.querySelectorAll('#' + role + '-dashboard .tab');
    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const tabContainer = this.closest('.tabs');
            tabContainer.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            
            const tabName = this.getAttribute('data-tab');
            filterArticles(tabName);
        });
    });

    // Load initial data
    loadDashboardData(role);
}

function showPage(page) {
    // Hide all pages
    const pages = document.querySelectorAll('#' + currentDashboard + '-dashboard .tab-content');
    pages.forEach(p => p.classList.remove('active'));
    
    // Show selected page
    const pageElement = document.getElementById(currentDashboard + '-' + page + '-page');
    if (pageElement) {
        pageElement.classList.add('active');
        
        // Update page title
        const titleElement = document.getElementById(currentDashboard + '-page-title');
        const pageTitles = {
            'dashboard': 'Dashboard ' + (currentDashboard === 'jurnalis' ? 'Jurnalis' : currentDashboard === 'editor' ? 'Editor' : 'Admin'),
            'tulis-artikel': 'Tulis Artikel Baru',
            'artikel-saya': 'Artikel Saya',
            'review-artikel': 'Review Artikel',
            'edit-artikel': 'Edit Artikel',
            'disetujui': 'Artikel Disetujui',
            'publikasi': 'Publikasi Artikel',
            'kelola-pengguna': 'Kelola Pengguna',
            'kategori': 'Kelola Kategori',
            'statistik': 'Statistik ' + (currentDashboard === 'jurnalis' ? 'Saya' : currentDashboard === 'editor' ? 'Editor' : 'Lengkap'),
            'pengaturan': 'Pengaturan Sistem'
        };
        
        titleElement.textContent = pageTitles[page] || 'Dashboard';
        
        // Load page-specific data
        loadPageData(page);
    }
}

// FUNGSI-FUNGSI API YANG BARU
async function fetchArticles() {
    try {
        const response = await fetch('articles.php');
        const result = await response.json();
        
        if (result.success) {
            return result.articles;
        } else {
            console.error('Gagal mengambil artikel:', result.message);
            return [];
        }
    } catch (error) {
        console.error('Error mengambil artikel:', error);
        return [];
    }
}

async function saveArticle(articleData) {
    try {
        const response = await fetch('articles.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(articleData)
        });
        
        const result = await response.json();
        return result;
    } catch (error) {
        console.error('Error menyimpan artikel:', error);
        return { success: false, message: 'Terjadi kesalahan saat menyimpan artikel' };
    }
}

async function updateArticleStatus(articleId, status) {
    try {
        const response = await fetch('articles.php', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                id: articleId,
                status: status
            })
        });
        
        const result = await response.json();
        return result;
    } catch (error) {
        console.error('Error update artikel:', error);
        return { success: false, message: 'Terjadi kesalahan' };
    }
}

async function loadDashboardData(role) {
    try {
        const articles = await fetchArticles();
        
        // Filter artikel berdasarkan role
        let filteredArticles = [];
        if (role === 'jurnalis') {
            filteredArticles = articles.filter(article => article.id_penulis === currentUser.id);
        } else if (role === 'editor') {
            filteredArticles = articles.filter(article => 
                article.status === 'pending' || article.status === 'review'
            );
        } else if (role === 'admin') {
            filteredArticles = articles;
        }
        
        const articleContainer = document.getElementById(role + '-article-list');
        if (articleContainer) {
            articleContainer.innerHTML = generateArticleHTML(filteredArticles, role);
        }
    } catch (error) {
        console.error('Error load dashboard:', error);
    }
}

async function loadPageData(page) {
    switch(page) {
        case 'artikel-saya':
            await loadMyArticles();
            break;
        case 'review-artikel':
            await loadReviewArticles();
            break;
        case 'edit-artikel':
            await loadEditArticles();
            break;
        case 'disetujui':
            await loadApprovedArticles();
            break;
        case 'publikasi':
            await loadPublishArticles();
            break;
        case 'kelola-pengguna':
            await loadUsers();
            break;
        case 'kategori':
            await loadCategories();
            break;
    }
}

// Fungsi-fungsi load data spesifik
async function loadMyArticles() {
    try {
        const articles = await fetchArticles();
        const myArticles = articles.filter(article => article.id_penulis === currentUser.id);
        const container = document.getElementById('jurnalis-my-articles');
        if (container) {
            container.innerHTML = generateArticleHTML(myArticles, 'jurnalis');
        }
    } catch (error) {
        console.error('Error load my articles:', error);
    }
}

async function loadReviewArticles() {
    try {
        const articles = await fetchArticles();
        const reviewArticles = articles.filter(article => 
            article.status === 'pending' || article.status === 'review'
        );
        const container = document.getElementById('editor-review-articles');
        if (container) {
            container.innerHTML = generateArticleHTML(reviewArticles, 'editor');
        }
    } catch (error) {
        console.error('Error load review articles:', error);
    }
}

async function loadEditArticles() {
    try {
        const articles = await fetchArticles();
        const editArticles = articles.filter(article => article.status === 'review');
        const container = document.getElementById('editor-edit-articles');
        if (container) {
            container.innerHTML = generateArticleHTML(editArticles, 'editor');
        }
    } catch (error) {
        console.error('Error load edit articles:', error);
    }
}

async function loadApprovedArticles() {
    try {
        const articles = await fetchArticles();
        const approvedArticles = articles.filter(article => article.status === 'published');
        const container = document.getElementById('editor-approved-articles');
        if (container) {
            container.innerHTML = generateArticleHTML(approvedArticles, 'editor');
        }
    } catch (error) {
        console.error('Error load approved articles:', error);
    }
}

async function loadPublishArticles() {
    try {
        const articles = await fetchArticles();
        const publishArticles = articles.filter(article => article.status === 'published');
        const container = document.getElementById('admin-publish-articles');
        if (container) {
            container.innerHTML = generateArticleHTML(publishArticles, 'admin');
        }
    } catch (error) {
        console.error('Error load publish articles:', error);
    }
}

// Fungsi generate HTML untuk artikel
function generateArticleHTML(articles, role) {
    if (!articles || articles.length === 0) {
        return '<p style="text-align: center; padding: 20px; color: #666;">Tidak ada artikel</p>';
    }
    
    return articles.map(article => {
        let statusClass = '';
        let statusText = '';
        
        switch(article.status) {
            case 'draft': statusClass = 'status-draft'; statusText = 'Draft'; break;
            case 'pending': statusClass = 'status-pending'; statusText = 'Menunggu Review'; break;
            case 'review': statusClass = 'status-review'; statusText = 'Dalam Review'; break;
            case 'published': statusClass = 'status-published'; statusText = 'Diterbitkan'; break;
            case 'rejected': statusClass = 'status-rejected'; statusText = 'Ditolak'; break;
        }
        
        let actions = '';
        if (role === 'jurnalis') {
            actions = `
                <button title="Edit" onclick="editArticle(${article.id})"><i class="fas fa-edit"></i></button>
                <button title="Kirim ke Editor" onclick="updateArticleStatus(${article.id}, 'pending')"><i class="fas fa-paper-plane"></i></button>
                ${article.status === 'draft' ? `<button title="Hapus" onclick="deleteArticle(${article.id})"><i class="fas fa-trash"></i></button>` : ''}
            `;
        } else if (role === 'editor') {
            actions = `
                <button title="Review" class="btn-primary" onclick="reviewArticle(${article.id})">Review</button>
                <button title="Terima" onclick="updateArticleStatus(${article.id}, 'published')"><i class="fas fa-check"></i></button>
                <button title="Tolak" onclick="updateArticleStatus(${article.id}, 'rejected')"><i class="fas fa-times"></i></button>
            `;
        } else if (role === 'admin') {
            actions = `
                <button title="Preview" onclick="previewArticle(${article.id})"><i class="fas fa-eye"></i></button>
                <button title="Terbitkan" class="btn-success" onclick="publishArticle(${article.id})">Terbitkan</button>
                <button title="Kembalikan" onclick="returnArticle(${article.id})"><i class="fas fa-undo"></i></button>
            `;
        }
        
        return `
            <div class="article-item">
                <img src="${article.gambar_sampul || 'https://via.placeholder.com/100x80'}" alt="Article" class="article-image">
                <div class="article-info">
                    <h4>${article.judul}</h4>
                    <div class="article-meta">
                        ${article.nama_penulis ? 'Oleh: ' + article.nama_penulis + ' • ' : ''}
                        ${article.nama_kategori ? 'Kategori: ' + article.nama_kategori + ' • ' : ''}
                        ${article.views ? 'Views: ' + article.views + ' • ' : ''}
                        ${formatDate(article.tanggal_dibuat)}
                    </div>
                    <span class="article-status ${statusClass}">${statusText}</span>
                </div>
                <div class="article-actions">
                    ${actions}
                </div>
            </div>
        `;
    }).join('');
}

// Form handler untuk artikel baru
document.getElementById('article-form')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const judul = document.getElementById('article-title').value;
    const id_kategori = document.getElementById('article-category').value;
    const tags = document.getElementById('article-tags').value;
    // const konten = document.getElementById('article-content').innerHTML;
const konten = document.getElementById('article-content').value;

    
    if (!judul || !id_kategori || !konten) {
        alert('Harap isi semua field yang wajib!');
        return;
    }
    
    const result = await saveArticle({
        judul: judul,
        konten: konten,
        id_kategori: id_kategori,
        tags: tags,
        status: 'pending'
    });
    
    if (result.success) {
        alert('Artikel berhasil dikirim ke editor!');
        showPage('artikel-saya');
    } else {
        alert('Gagal menyimpan artikel: ' + result.message);
    }
});

// Fungsi-fungsi bantuan
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'short',
        year: 'numeric'
    });
}

function logout() {
    // Hapus session di server
    fetch('logout.php').then(() => {
        // Reset UI
        document.getElementById('login-container').style.display = 'flex';
        document.getElementById('jurnalis-dashboard').style.display = 'none';
        document.getElementById('editor-dashboard').style.display = 'none';
        document.getElementById('admin-dashboard').style.display = 'none';
        
        // Reset form
        document.getElementById('login-form').reset();
        
        // Reset ke role default
        currentRole = 'jurnalis';
        document.querySelectorAll('.role-option').forEach(opt => opt.classList.remove('active'));
        document.querySelector('.role-option.jurnalis').classList.add('active');
        
        currentUser = null;
    });
}

// Fungsi-fungsi aksi artikel (placeholder)
function editArticle(id) {
    alert('Edit artikel: ' + id);
}

function deleteArticle(id) {
    if (confirm('Apakah Anda yakin ingin menghapus artikel ini?')) {
        alert('Artikel berhasil dihapus!');
    }
}

function reviewArticle(id) {
    alert('Review artikel: ' + id);
}

function previewArticle(id) {
    alert('Preview artikel: ' + id);
}

function publishArticle(id) {
    if (confirm('Apakah Anda yakin ingin mempublikasikan artikel ini?')) {
        updateArticleStatus(id, 'published');
    }
}

function returnArticle(id) {
    if (confirm('Kembalikan artikel ke editor?')) {
        updateArticleStatus(id, 'review');
    }
}

// Modal functions
function showAddUserModal() {
    document.getElementById('add-user-modal').style.display = 'flex';
}

function showAddCategoryModal() {
    document.getElementById('add-category-modal').style.display = 'flex';
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

// Filter articles
function filterArticles(filter) {
    console.log('Filtering articles by:', filter);
}

// User management functions
async function loadUsers() {
    // Implementasi nanti
    console.log('Load users');
}

async function loadCategories() {
    // Implementasi nanti
    console.log('Load categories');
}

// Save draft function
async function saveDraft() {
    const judul = document.getElementById('article-title').value;
    const id_kategori = document.getElementById('article-category').value;
    const tags = document.getElementById('article-tags').value;
    // const konten = document.getElementById('article-content').innerHTML;
const konten = document.getElementById('article-content').value;

    
    const result = await saveArticle({
        judul: judul,
        konten: konten,
        id_kategori: id_kategori,
        tags: tags,
        status: 'draft'
    });
    
    if (result.success) {
        alert('Draft berhasil disimpan!');
    } else {
        alert('Gagal menyimpan draft: ' + result.message);
    }
}