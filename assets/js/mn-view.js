/**
 * MN View Widget JavaScript
 * 
 * Handles popup/modal functionality and file viewing
 * @since 1.4.1
 */

class MNViewWidget {
    constructor(element) {
        this.element = element;
        this.popup = element.querySelector('.mn-view-popup');
        this.popupOverlay = element.querySelector('.mn-view-popup-overlay');
        this.popupContent = element.querySelector('.mn-view-popup-content');
        this.popupTitle = element.querySelector('.mn-view-popup-title');
        this.popupBody = element.querySelector('.mn-view-popup-body');
        this.fileContainer = element.querySelector('.mn-view-file-container');
        this.closeButton = element.querySelector('.mn-view-popup-close');
        this.zoomInButton = element.querySelector('.mn-view-zoom-in');
        this.zoomOutButton = element.querySelector('.mn-view-zoom-out');
        this.zoomLevel = element.querySelector('.mn-view-zoom-level');
        
        this.currentZoom = 1;
        this.minZoom = 0.1;
        this.maxZoom = 5;
        this.zoomStep = 0.1;
        
        this.isDragging = false;
        this.dragStart = { x: 0, y: 0 };
        this.dragOffset = { x: 0, y: 0 };
        
        this.init();
    }
    
    init() {
        this.bindEvents();
    }
    
    bindEvents() {
        // View buttons
        const viewButtons = this.element.querySelectorAll('.mn-view-button');
        viewButtons.forEach(button => {
            button.addEventListener('click', (e) => this.openFile(e));
        });
        
        // Close popup
        if (this.closeButton) {
            this.closeButton.addEventListener('click', () => this.closePopup());
        }
        
        if (this.popupOverlay) {
            this.popupOverlay.addEventListener('click', () => this.closePopup());
        }
        
        // Zoom controls
        if (this.zoomInButton) {
            this.zoomInButton.addEventListener('click', () => this.zoomIn());
        }
        
        if (this.zoomOutButton) {
            this.zoomOutButton.addEventListener('click', () => this.zoomOut());
        }
        
        // Keyboard events
        document.addEventListener('keydown', (e) => this.handleKeydown(e));
        
        // Prevent popup content click from closing popup
        if (this.popupContent) {
            this.popupContent.addEventListener('click', (e) => e.stopPropagation());
        }
    }
    
    openFile(event) {
        event.preventDefault();
        
        const button = event.currentTarget;
        const fileUrl = button.getAttribute('data-file-url');
        const fileType = button.getAttribute('data-file-type');
        const fileTitle = button.getAttribute('data-file-title');
        
        if (!fileUrl) {
            console.error('No file URL provided');
            return;
        }
        
        this.showPopup();
        this.setPopupTitle(fileTitle || 'File Viewer');
        this.loadFile(fileUrl, fileType);
    }
    
    showPopup() {
        if (this.popup) {
            this.popup.style.display = 'flex';
            // Force reflow
            this.popup.offsetHeight;
            this.popup.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
    }
    
    closePopup() {
        if (this.popup) {
            this.popup.classList.remove('active');
            setTimeout(() => {
                this.popup.style.display = 'none';
                this.clearFileContainer();
                this.resetZoom();
            }, 300);
            document.body.style.overflow = '';
        }
    }
    
    setPopupTitle(title) {
        if (this.popupTitle) {
            this.popupTitle.textContent = title;
        }
    }
    
    loadFile(fileUrl, fileType) {
        this.showLoading();
        
        switch (fileType) {
            case 'pdf':
                this.loadPDF(fileUrl);
                break;
            case 'image':
                this.loadImage(fileUrl);
                break;
            case 'video':
                this.loadVideo(fileUrl);
                break;
            default:
                this.loadPDF(fileUrl); // Default to PDF
                break;
        }
    }
    
    loadPDF(fileUrl) {
        const iframe = document.createElement('iframe');
        iframe.className = 'mn-view-pdf-viewer';
        iframe.src = fileUrl;
        iframe.title = 'PDF Viewer';
        
        iframe.onload = () => {
            this.hideLoading();
        };
        
        iframe.onerror = () => {
            this.showError('Failed to load PDF file');
        };
        
        this.setFileContainer(iframe);
    }
    
    loadImage(fileUrl) {
        const img = document.createElement('img');
        img.className = 'mn-view-image-viewer';
        img.src = fileUrl;
        img.alt = 'Image Viewer';
        img.draggable = false;
        
        img.onload = () => {
            this.hideLoading();
            this.bindImageEvents(img);
        };
        
        img.onerror = () => {
            this.showError('Failed to load image file');
        };
        
        this.setFileContainer(img);
    }
    
    loadVideo(fileUrl) {
        const video = document.createElement('video');
        video.className = 'mn-view-video-viewer';
        video.src = fileUrl;
        video.controls = true;
        video.preload = 'metadata';
        
        video.onloadedmetadata = () => {
            this.hideLoading();
        };
        
        video.onerror = () => {
            this.showError('Failed to load video file');
        };
        
        this.setFileContainer(video);
    }
    
    bindImageEvents(img) {
        // Mouse wheel zoom
        this.fileContainer.addEventListener('wheel', (e) => {
            if (e.ctrlKey || e.metaKey) {
                e.preventDefault();
                if (e.deltaY < 0) {
                    this.zoomIn();
                } else {
                    this.zoomOut();
                }
            }
        });
        
        // Drag to pan when zoomed
        img.addEventListener('mousedown', (e) => this.startDrag(e));
        document.addEventListener('mousemove', (e) => this.drag(e));
        document.addEventListener('mouseup', () => this.endDrag());
        
        // Touch events for mobile
        img.addEventListener('touchstart', (e) => this.startDrag(e.touches[0]));
        document.addEventListener('touchmove', (e) => {
            if (this.isDragging) {
                e.preventDefault();
                this.drag(e.touches[0]);
            }
        });
        document.addEventListener('touchend', () => this.endDrag());
    }
    
    startDrag(event) {
        if (this.currentZoom > 1) {
            this.isDragging = true;
            this.dragStart.x = event.clientX - this.dragOffset.x;
            this.dragStart.y = event.clientY - this.dragOffset.y;
            
            const img = this.fileContainer.querySelector('.mn-view-image-viewer');
            if (img) {
                img.style.cursor = 'grabbing';
                img.classList.add('zoomed');
            }
        }
    }
    
    drag(event) {
        if (this.isDragging) {
            this.dragOffset.x = event.clientX - this.dragStart.x;
            this.dragOffset.y = event.clientY - this.dragStart.y;
            
            const img = this.fileContainer.querySelector('.mn-view-image-viewer');
            if (img) {
                img.style.transform = `scale(${this.currentZoom}) translate(${this.dragOffset.x}px, ${this.dragOffset.y}px)`;
            }
        }
    }
    
    endDrag() {
        this.isDragging = false;
        const img = this.fileContainer.querySelector('.mn-view-image-viewer');
        if (img) {
            img.style.cursor = this.currentZoom > 1 ? 'move' : 'grab';
        }
    }
    
    zoomIn() {
        if (this.currentZoom < this.maxZoom) {
            this.currentZoom = Math.min(this.currentZoom + this.zoomStep, this.maxZoom);
            this.applyZoom();
        }
    }
    
    zoomOut() {
        if (this.currentZoom > this.minZoom) {
            this.currentZoom = Math.max(this.currentZoom - this.zoomStep, this.minZoom);
            this.applyZoom();
        }
    }
    
    applyZoom() {
        const img = this.fileContainer.querySelector('.mn-view-image-viewer');
        if (img) {
            if (this.currentZoom === 1) {
                this.dragOffset = { x: 0, y: 0 };
                img.style.transform = 'scale(1)';
                img.style.cursor = 'grab';
                img.classList.remove('zoomed');
            } else {
                img.style.transform = `scale(${this.currentZoom}) translate(${this.dragOffset.x}px, ${this.dragOffset.y}px)`;
                img.style.cursor = 'move';
                img.classList.add('zoomed');
            }
        }
        
        this.updateZoomLevel();
    }
    
    updateZoomLevel() {
        if (this.zoomLevel) {
            this.zoomLevel.textContent = Math.round(this.currentZoom * 100) + '%';
        }
    }
    
    resetZoom() {
        this.currentZoom = 1;
        this.dragOffset = { x: 0, y: 0 };
        this.updateZoomLevel();
    }
    
    setFileContainer(element) {
        this.clearFileContainer();
        this.fileContainer.appendChild(element);
    }
    
    clearFileContainer() {
        if (this.fileContainer) {
            this.fileContainer.innerHTML = '';
        }
    }
    
    showLoading() {
        const loadingDiv = document.createElement('div');
        loadingDiv.className = 'mn-view-loading';
        loadingDiv.innerHTML = `
            <div class="mn-view-loading-spinner"></div>
            <div class="mn-view-loading-text">Loading file...</div>
        `;
        this.setFileContainer(loadingDiv);
    }
    
    hideLoading() {
        const loading = this.fileContainer.querySelector('.mn-view-loading');
        if (loading) {
            loading.remove();
        }
    }
    
    showError(message) {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'mn-view-error';
        errorDiv.innerHTML = `
            <div class="mn-view-error-icon">⚠️</div>
            <div class="mn-view-error-text">${message}</div>
        `;
        this.setFileContainer(errorDiv);
    }
    
    handleKeydown(event) {
        if (!this.popup.classList.contains('active')) {
            return;
        }
        
        switch (event.key) {
            case 'Escape':
                this.closePopup();
                break;
            case '+':
            case '=':
                if (event.ctrlKey || event.metaKey) {
                    event.preventDefault();
                    this.zoomIn();
                }
                break;
            case '-':
                if (event.ctrlKey || event.metaKey) {
                    event.preventDefault();
                    this.zoomOut();
                }
                break;
            case '0':
                if (event.ctrlKey || event.metaKey) {
                    event.preventDefault();
                    this.currentZoom = 1;
                    this.applyZoom();
                }
                break;
        }
    }
}

// Initialize widgets when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    const widgets = document.querySelectorAll('.mn-view-widget');
    widgets.forEach(widget => {
        new MNViewWidget(widget);
    });
});

// Initialize widgets for Elementor preview
jQuery(window).on('elementor/frontend/init', function() {
    elementorFrontend.hooks.addAction('frontend/element_ready/mn-view.default', function($scope) {
        const widget = $scope.find('.mn-view-widget')[0];
        if (widget) {
            new MNViewWidget(widget);
        }
    });
});
