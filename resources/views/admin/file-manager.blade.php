@extends('layouts.admin')

@section('page-title', 'File Manager')

@section('content')
<style>
    .fm-container {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .fm-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .fm-toolbar {
        background: #f8f9fa;
        padding: 15px 20px;
        border-bottom: 1px solid #e0e0e0;
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        align-items: center;
    }

    .fm-breadcrumb {
        background: #f8f9fa;
        padding: 10px 20px;
        font-size: 0.9rem;
        border-bottom: 1px solid #e0e0e0;
    }

    .fm-breadcrumb a {
        color: #667eea;
        text-decoration: none;
        cursor: pointer;
        margin: 0 3px;
    }

    .fm-breadcrumb a:hover {
        text-decoration: underline;
    }

    .fm-main {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        gap: 10px;
        padding: 20px;
        min-height: 400px;
        max-height: 600px;
        overflow-y: auto;
    }

    .fm-item {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 15px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        background: #fafafa;
    }

    .fm-item:hover {
        background: #f0f0f0;
        border-color: #667eea;
        box-shadow: 0 2px 8px rgba(102, 126, 234, 0.15);
    }

    .fm-item-icon {
        font-size: 2.5rem;
        margin-bottom: 10px;
        color: #667eea;
    }

    .fm-item-name {
        font-size: 0.85rem;
        font-weight: 500;
        word-break: break-word;
        max-height: 2.5re;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }

    .fm-item-menu {
        position: absolute;
        top: 5px;
        right: 5px;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .fm-item:hover .fm-item-menu {
        opacity: 1;
    }

    .btn-menu {
        background: white;
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 4px 8px;
        cursor: pointer;
        font-size: 0.75rem;
    }

    .btn-menu:hover {
        background: #667eea;
        color: white;
        border-color: #667eea;
    }

    .fm-empty {
        grid-column: 1 / -1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 300px;
        color: #999;
    }

    .loader {
        display: none;
        text-align: center;
        padding: 20px;
        color: #667eea;
    }

    .loader.active {
        display: block;
    }

    .progress {
        display: none;
        margin-top: 10px;
    }

    .progress.active {
        display: block;
    }

    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1000;
        align-items: center;
        justify-content: center;
    }

    .modal-overlay.active {
        display: flex;
    }

    .modal-dialog {
        background: white;
        border-radius: 8px;
        padding: 25px;
        min-width: 300px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
    }

    .modal-dialog h5 {
        margin-bottom: 15px;
        color: #1a1a1a;
    }

    .modal-dialog input {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 6px;
        margin-bottom: 15px;
        font-size: 0.95rem;
    }

    .modal-buttons {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
    }

    .tooltip {
        position: relative;
        display: inline-block;
    }

    .tooltip .tooltiptext {
        visibility: hidden;
        background-color: #333;
        color: #fff;
        text-align: center;
        padding: 5px 10px;
        border-radius: 6px;
        position: absolute;
        z-index: 1;
        bottom: 125%;
        left: 50%;
        margin-left: -50px;
        opacity: 0;
        transition: opacity 0.3s;
        white-space: nowrap;
        font-size: 0.8rem;
    }

    .tooltip:hover .tooltiptext {
        visibility: visible;
        opacity: 1;
    }

    .image-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
        gap: 10px;
        padding: 20px;
    }

    .image-tile {
        position: relative;
        aspect-ratio: 1;
        border-radius: 8px;
        overflow: hidden;
        border: 2px solid #e0e0e0;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .image-tile:hover {
        border-color: #667eea;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.2);
        transform: scale(1.05);
    }

    .image-tile img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
</style>

<div class="fm-container">
    <!-- Header -->
    <div class="fm-header">
        <div>
            <h4 style="margin: 0;">
                <i class="bi bi-folder2-open"></i> File Manager
            </h4>
        </div>
        <div style="font-size: 0.9rem; opacity: 0.9;">
            Total Files: <span id="fileCount">0</span>
        </div>
    </div>

    <!-- Toolbar -->
    <div class="fm-toolbar">
        <button class="btn btn-primary btn-sm" onclick="triggerUpload()">
            <i class="bi bi-cloud-upload"></i> Upload Files
        </button>
        <button class="btn btn-success btn-sm" onclick="openCreateFolder()">
            <i class="bi bi-folder-plus"></i> New Folder
        </button>
        <input type="file" id="fileInput" multiple style="display: none;" onchange="handleFileUpload(event)">
    </div>

    <!-- Breadcrumb -->
    <div class="fm-breadcrumb">
        <a onclick="navigateTo('')"><i class="bi bi-house"></i> Storage</a>
        <span id="breadcrumbPath"></span>
    </div>

    <!-- Loader -->
    <div class="loader" id="loader">
        <i class="fas fa-spinner fa-spin"></i> Loading files...
    </div>

    <!-- Main Content -->
    <div class="fm-main" id="fileList"></div>
</div>

<!-- Create Folder Modal -->
<div class="modal-overlay" id="createFolderModal">
    <div class="modal-dialog">
        <h5>Create New Folder</h5>
        <input type="text" id="folderNameInput" placeholder="Enter folder name" autocomplete="off">
        <div class="modal-buttons">
            <button class="btn btn-secondary btn-sm" onclick="closeCreateFolder()">Cancel</button>
            <button class="btn btn-primary btn-sm" onclick="submitCreateFolder()">Create</button>
        </div>
    </div>
</div>

<!-- Rename Modal -->
<div class="modal-overlay" id="renameModal">
    <div class="modal-dialog">
        <h5>Rename Item</h5>
        <input type="text" id="renameInput" placeholder="Enter new name" autocomplete="off">
        <div class="modal-buttons">
            <button class="btn btn-secondary btn-sm" onclick="closeRenameModal()">Cancel</button>
            <button class="btn btn-primary btn-sm" onclick="submitRename()">Rename</button>
        </div>
    </div>
</div>

<!-- Image Preview Modal -->
<div class="modal-overlay" id="previewModal">
    <div class="modal-dialog" style="background: transparent; box-shadow: none; max-width: 90vw; min-width: auto;">
        <button style="position: absolute; top: 10px; right: 10px; background: white; border: none; border-radius: 50%; width: 40px; height: 40px; cursor: pointer; font-size: 1.5rem;" onclick="closePreview()">×</button>
        <img id="previewImage" src="" alt="Preview" style="max-width: 100%; max-height: 80vh; border-radius: 8px;">
    </div>
</div>

<script>
    let currentPath = '';
    let renameItemPath = '';

    document.addEventListener('DOMContentLoaded', function() {
        loadFiles();
    });

    function loadFiles(path = '') {
        currentPath = path;
        document.getElementById('loader').classList.add('active');

        fetch("{{ route('admin.file-manager.list') }}?path=" + encodeURIComponent(path))
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    displayFiles(data.items);
                    updateBreadcrumb(path);
                    document.getElementById('fileCount').textContent = data.items.length;
                } else {
                    showError(data.message || 'Error loading files');
                }
            })
            .catch(error => showError('Error: ' + error))
            .finally(() => document.getElementById('loader').classList.remove('active'));
    }

    function displayFiles(items) {
        const fileList = document.getElementById('fileList');
        fileList.innerHTML = '';

        // Limit display to 100 items to improve performance
        const maxItems = 100;
        const displayItems = items.slice(0, maxItems);
        const hasMore = items.length > maxItems;

        if (currentPath) {
            // Add "Up" button
            const upItem = document.createElement('div');
            upItem.className = 'fm-item';
            upItem.style.cursor = 'pointer';
            upItem.onclick = () => navigateUp();
            upItem.innerHTML = `
                <div class="fm-item-icon">⬆️</div>
                <div class="fm-item-name">Go Up</div>
            `;
            fileList.appendChild(upItem);
        }

        if (displayItems.length === 0) {
            fileList.innerHTML = '<div class="fm-empty"><i class="fas fa-folder-open"></i><p>No files or folders</p></div>';
            return;
        }

        displayItems.forEach(item => {
            const itemEl = document.createElement('div');
            itemEl.className = 'fm-item';
            
            let icon, actions;
            let isImage = /\.(jpg|jpeg|png|gif|webp|bmp)$/i.test(item.name);
            
            if (item.type === 'folder') {
                icon = '📁';
                actions = `
                    <button class="btn-menu" onclick="openRenameModal('${item.path}')" title="Rename"><i class="bi bi-pencil"></i></button>
                    <button class="btn-menu" onclick="deleteItem('${item.path}')" title="Delete"><i class="bi bi-trash"></i></button>
                `;
                itemEl.style.cursor = 'pointer';
                itemEl.onclick = () => navigateTo(\`${item.path}\`);
            } else {
                icon = getFileIcon(item.name);
                let downloadButton = `<a href="${item.url}" download class="btn-menu" title="Download"><i class="bi bi-download"></i></a>`;
                let previewButton = isImage ? `<button class="btn-menu" onclick="previewImage('${item.url}')" title="Preview"><i class="bi bi-eye"></i></button>` : '';
                actions = `
                    ${downloadButton}
                    ${previewButton}
                    <button class="btn-menu" onclick="openRenameModal('${item.path}')" title="Rename"><i class="bi bi-pencil"></i></button>
                    <button class="btn-menu" onclick="deleteItem('${item.path}')" title="Delete"><i class="bi bi-trash"></i></button>
                `;
            }

            itemEl.innerHTML = `
                <div class="fm-item-menu">
                    ${actions}
                </div>
                <div class="fm-item-icon">${icon}</div>
                <div class="fm-item-name">${item.name}</div>
                ${item.type === 'file' ? `<small style="color: #999; font-size: 0.75rem;">${formatFileSize(item.size)}</small>` : ''}
            `;
            
            fileList.appendChild(itemEl);
        });

        // Show message if there are more items
        if (hasMore) {
            const moreItem = document.createElement('div');
            moreItem.className = 'fm-item';
            moreItem.style.opacity = '0.6';
            moreItem.innerHTML = `
                <div style="font-size: 0.85rem; color: #999; padding: 10px;">
                    📂 ${items.length - maxItems} more items not shown
                </div>
            `;
            fileList.appendChild(moreItem);
        }
    }

    function updateBreadcrumb(path) {
        const breadcrumbPath = document.getElementById('breadcrumbPath');
        if (!path) {
            breadcrumbPath.innerHTML = '';
            return;
        }

        const parts = path.split('/').filter(p => p);
        let html = '';
        let currentPath = '';

        parts.forEach((part, index) => {
            currentPath += (currentPath ? '/' : '') + part;
            html += ` / <a onclick="navigateTo('${currentPath}')">${part}</a>`;
        });

        breadcrumbPath.innerHTML = html;
    }

    function navigateTo(path) {
        loadFiles(path);
    }

    function navigateUp() {
        const parts = currentPath.split('/').filter(p => p);
        parts.pop();
        navigateTo(parts.join('/'));
    }

    function triggerUpload() {
        document.getElementById('fileInput').click();
    }

    function handleFileUpload(event) {
        const files = event.target.files;
        for (let file of files) {
            uploadFile(file);
        }
        event.target.value = '';
    }

    function uploadFile(file) {
        console.log('Uploading file:', file.name, 'Size:', file.size, 'Type:', file.type);
        
        // Check file size (100MB max)
        if (file.size > 102400 * 1024) {
            showError('File too large. Maximum size is 100MB');
            return;
        }

        const formData = new FormData();
        formData.append('file', file);
        formData.append('path', currentPath);

        const progressBar = document.createElement('div');
        progressBar.className = 'progress active';
        progressBar.innerHTML = `<div class="progress-bar" role="progressbar" style="width: 0%">${file.name}</div>`;

        fetch("{{ route('admin.file-manager.upload') }}", {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token()}}',
            }
        })
        .then(response => {
            console.log('Upload response status:', response.status);
            if (!response.ok) {
                return response.text().then(text => {
                    throw new Error(`HTTP ${response.status}: ${text}`);
                });
            }
            return response.json();
        })
        .then(data => {
            console.log('Upload success:', data);
            if (data.success) {
                loadFiles(currentPath);
                showSuccess('File uploaded: ' + file.name);
            } else {
                showError('Upload failed: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Upload error:', error);
            showError('Upload error: ' + error.message);
        });
    }

    function openCreateFolder() {
        document.getElementById('createFolderModal').classList.add('active');
        document.getElementById('folderNameInput').focus();
    }

    function closeCreateFolder() {
        document.getElementById('createFolderModal').classList.remove('active');
        document.getElementById('folderNameInput').value = '';
    }

    function submitCreateFolder() {
        const folderName = document.getElementById('folderNameInput').value.trim();
        if (!folderName) {
            document.getElementById('folderNameInput').focus();
            return;
        }

        // Get the button properly
        const submitBtn = document.querySelector('#createFolderModal .btn-primary');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Creating...';
        }

        console.log('Creating folder:', { folderName, currentPath });

        fetch("{{ route('admin.file-manager.create-folder') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: JSON.stringify({
                folderName: folderName,
                path: currentPath,
            })
        })
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                return response.text().then(text => {
                    throw new Error(`HTTP ${response.status}: ${text}`);
                });
            }
            return response.json();
        })
        .then(data => {
            console.log('Success response:', data);
            if (data.success) {
                closeCreateFolder();
                // Reload files after a small delay to ensure folder is written to disk
                setTimeout(() => {
                    loadFiles(currentPath);
                    showSuccess('Folder "' + folderName + '" created successfully');
                }, 200);
            } else {
                showError('Error: ' + (data.message || 'Failed to create folder'));
            }
        })
        .catch(error => {
            console.error('Create folder error:', error);
            showError('Error: ' + error.message);
        })
        .finally(() => {
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Create';
            }
        });
    }

    function openRenameModal(path) {
        renameItemPath = path;
        const itemName = path.split('/').pop();
        document.getElementById('renameInput').value = itemName;
        document.getElementById('renameModal').classList.add('active');
        document.getElementById('renameInput').focus();
        document.getElementById('renameInput').select();
    }

    function closeRenameModal() {
        document.getElementById('renameModal').classList.remove('active');
        renameItemPath = '';
    }

    function submitRename() {
        const newName = document.getElementById('renameInput').value.trim();
        if (!newName) {
            alert('Please enter a new name');
            return;
        }

        fetch("{{ route('admin.file-manager.rename') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: JSON.stringify({
                path: renameItemPath,
                newName: newName,
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadFiles(currentPath);
                closeRenameModal();
                showSuccess('Renamed successfully');
            } else {
                showError('Error: ' + data.message);
            }
        })
        .catch(error => showError('Error: ' + error));
    }

    function deleteItem(path) {
        if (!confirm('Are you sure you want to delete this item?')) return;

        fetch("{{ route('admin.file-manager.delete') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: JSON.stringify({ path: path })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadFiles(currentPath);
                showSuccess('Deleted successfully');
            } else {
                showError('Error: ' + data.message);
            }
        })
        .catch(error => showError('Error: ' + error));
    }

    function downloadFile(url, name) {
        const a = document.createElement('a');
        a.href = url;
        a.download = name;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
    }

    function getFileIcon(filename) {
        const ext = filename.split('.').pop().toLowerCase();
        const icons = {
            'pdf': '📄',
            'doc': '📝', 'docx': '📝', 'txt': '📝',
            'xls': '📊', 'xlsx': '📊', 'csv': '📊',
            'jpg': '🖼️', 'jpeg': '🖼️', 'png': '🖼️', 'gif': '🖼️', 'webp': '🖼️', 'bmp': '🖼️',
            'mp4': '🎬', 'avi': '🎬', 'mov': '🎬', 'mkv': '🎬',
            'mp3': '🎵', 'wav': '🎵', 'flac': '🎵',
            'zip': '📦', 'rar': '📦', '7z': '📦',
            'php': '⚙️', 'js': '⚙️', 'css': '🎨', 'html': '🌐', 'json': '{}'
        };
        return icons[ext] || '📄';
    }

    function formatFileSize(bytes) {
        if (bytes === 0) return '0 B';
        const k = 1024;
        const sizes = ['B', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    function showSuccess(message) {
        const alert = document.createElement('div');
        alert.className = 'alert alert-success alert-dismissible fade show';
        alert.setAttribute('role', 'alert');
        alert.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999; max-width: 400px;';
        alert.innerHTML = `
            <i class="bi bi-check-circle"></i> ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.body.appendChild(alert);
        console.log('Showing success:', message);
        setTimeout(() => alert.remove(), 3000);
    }

    function showError(message) {
        const alert = document.createElement('div');
        alert.className = 'alert alert-danger alert-dismissible fade show';
        alert.setAttribute('role', 'alert');
        alert.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999; max-width: 400px;';
        alert.innerHTML = `
            <i class="bi bi-exclamation-circle"></i> ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.body.appendChild(alert);
        console.error('Showing error:', message);
        setTimeout(() => alert.remove(), 5000);
    }

    // Prevent default drag behavior
    document.addEventListener('dragover', e => e.preventDefault());
    document.addEventListener('drop', e => {
        e.preventDefault();
        const files = e.dataTransfer.files;
        for (let file of files) {
            uploadFile(file);
        }
    });

    function previewImage(url) {
        document.getElementById('previewImage').src = url;
        document.getElementById('previewModal').classList.add('active');
    }

    function closePreview() {
        document.getElementById('previewModal').classList.remove('active');
    }
</script>
@endsection
