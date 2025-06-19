// =====================================================
// MAIN APPLICATION STATE
// =====================================================
const TsubakilFloral = {
    // Shopping cart
    cart: {
        items: [],
        total: 0,
        count: 0
    },
    
    // Sample products (fallback data)
    products: [
        {
            id: 1,
            name: 'Heavenly Grace',
            price: 15000,
            image: 'assets/images/img11.jpg',
            description: 'A delicate orchids — pure, airy, and refined.',
            rating: 5,
            reviews: 24
        },
        {
            id: 2,
            name: 'Sunflower Sunshine',
            price: 5500,
            image: 'assets/images/img10.jpg',
            description: 'Bright sunflowers with complementary greens',
            rating: 4,
            reviews: 18
        },
        {
            id: 3,
            name: 'Spring Garden Mix',
            price: 8500,
            image: 'assets/images/img9.jpg',
            description: 'Mixed seasonal flowers in vibrant colors',
            rating: 5,
            reviews: 31
        },
        {
            id: 4,
            name: 'Classic Red Roses',
            price: 12000,
            image: 'assets/images/img6.jpg',
            description: 'Traditional dozen red roses',
            rating: 4,
            reviews: 12
        },
        {
            id: 5,
            name: 'Elegant White Lilies',
            price: 7000,
            image: 'assets/images/img8.jpg',
            description: 'Bright sunflowers with mixed seasonal flowers',
            rating: 5,
            reviews: 28
        },
        {
            id: 6,
            name: 'Whispers of White',
            price: 6000,
            image: 'assets/images/img.jpg',
            description: 'A soft, cloud-like bouquet of tinted pink baby\'s breath.',
            rating: 5,
            reviews: 19
        }
    ],
};

// =====================================================
// UTILITY FUNCTIONS
// =====================================================
const Utils = {
    // Format price in Japanese Yen
    formatPrice: (price) => {
        return new Intl.NumberFormat('ja-JP', {
            style: 'currency',
            currency: 'JPY',
            minimumFractionDigits: 0
        }).format(price);
    },
    
    // Show notification toast
    showToast: (message, type = 'success') => {
        const toast = document.createElement('div');
        toast.className = `toast align-items-center text-white bg-${type} border-0 position-fixed`;
        toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999;';
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body fw-bold">
                    <i class="bi bi-check-circle me-2"></i>
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;
        
        document.body.appendChild(toast);
        const bsToast = new bootstrap.Toast(toast);
        bsToast.show();
        
        // Remove toast after hiding
        toast.addEventListener('hidden.bs.toast', () => {
            if (document.body.contains(toast)) {
                document.body.removeChild(toast);
            }
        });
    },
    
    // Generate star rating HTML
    generateStars: (rating) => {
        let stars = '';
        for (let i = 1; i <= 5; i++) {
            stars += `<i class="bi bi-star${i <= rating ? '-fill' : ''} text-warning"></i>`;
        }
        return stars;
    },
    
    // Debounce function for search
    debounce: (func, wait) => {
        let timeout;
        return function(...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), wait);
        };
    }
};

// =====================================================
// SHOPPING CART MANAGEMENT
// =====================================================
const Cart = {
    // Initialize cart
    init: () => {
        Cart.updateUI();
        Cart.bindEvents();
    },
    
    // Add item to cart
    addItem: (productId) => {
        console.log('Adding item to cart:', productId);
        const product = TsubakilFloral.products.find(p => p.id === parseInt(productId));
        if (!product) {
            console.error('Product not found:', productId);
            Utils.showToast('Product not found!', 'danger');
            return;
        }
        
        const existingItem = TsubakilFloral.cart.items.find(item => item.id === parseInt(productId));
        
        if (existingItem) {
            existingItem.quantity += 1;
        } else {
            TsubakilFloral.cart.items.push({ ...product, quantity: 1 });
        }
        
        Cart.updateTotals();
        Cart.updateUI();
        Utils.showToast(`${product.name} added to cart!`);
        console.log('Cart updated:', TsubakilFloral.cart);
    },
    
    // Update cart totals
    updateTotals: () => {
        TsubakilFloral.cart.total = TsubakilFloral.cart.items.reduce(
            (sum, item) => sum + (item.price * item.quantity), 0
        );
        TsubakilFloral.cart.count = TsubakilFloral.cart.items.reduce(
            (sum, item) => sum + item.quantity, 0
        );
    },
    
    // Update cart UI
    updateUI: () => {
        const cartCount = document.getElementById('cartCount');
        if (cartCount) {
            cartCount.textContent = TsubakilFloral.cart.count;
            // Add bounce animation
            cartCount.style.transform = 'scale(1.3)';
            setTimeout(() => cartCount.style.transform = 'scale(1)', 200);
        }
        
        // Update cart badge in navbar if exists
        const cartBadges = document.querySelectorAll('.cart-badge, .badge');
        cartBadges.forEach(badge => {
            if (badge.closest('.nav-link') || badge.closest('[href*="cart"]')) {
                badge.textContent = TsubakilFloral.cart.count;
                if (TsubakilFloral.cart.count > 0) {
                    badge.style.display = 'inline-block';
                } else {
                    badge.style.display = 'none';
                }
            }
        });
    },
    
    // Bind cart events
    bindEvents: () => {
        // Use event delegation for dynamically added buttons
        document.addEventListener('click', (e) => {
            // Handle add to cart buttons
            const addToCartBtn = e.target.closest('[data-add-to-cart]');
            if (addToCartBtn) {
                e.preventDefault();
                e.stopPropagation();
                const productId = addToCartBtn.dataset.addToCart;
                console.log('Add to cart clicked for product:', productId);
                Cart.addItem(productId);
            }
        });
    }
};

// =====================================================
// PRODUCT MANAGEMENT
// =====================================================
const Products = {
    // Initialize products
    init: () => {
        // Wait a bit for DOM to be fully ready
        setTimeout(() => {
            Products.extractProductsFromDOM();
            Products.setupProductCards();
            Products.setupQuickView();
        }, 100);
    },

    // Extract products from existing DOM elements
    extractProductsFromDOM: () => {
        const productCards = document.querySelectorAll('.product-card, .card');
        console.log(`Found ${productCards.length} product cards in DOM`);
        
        productCards.forEach((card, index) => {
            try {
                // Get product ID from various possible sources
                let productId = null;
                
                // Check for existing data attributes
                const quickViewBtn = card.querySelector('[data-quick-view]');
                const addToCartBtn = card.querySelector('[data-add-to-cart]');
                
                if (quickViewBtn && quickViewBtn.dataset.quickView) {
                    productId = parseInt(quickViewBtn.dataset.quickView);
                } else if (addToCartBtn && addToCartBtn.dataset.addToCart) {
                    productId = parseInt(addToCartBtn.dataset.addToCart);
                } else {
                    // Generate ID based on index
                    productId = index + 1;
                }
                
                // Extract product information from card
                const img = card.querySelector('img');
                const title = card.querySelector('.card-title, h5, h6, h4, h3, .product-title, .title');
                const description = card.querySelector('.card-text, .text-muted:not(.small), .description');
                const priceElement = card.querySelector('.text-primary, .fw-bold, .price, .h5');
                
                if (img && title) {
                    const productData = {
                        id: productId,
                        name: title.textContent.trim(),
                        image: img.src || img.dataset.src || 'https://via.placeholder.com/400x300/f8f9fa/6c757d?text=Product+Image',
                        description: description ? description.textContent.trim() : 'Beautiful flower arrangement',
                        price: priceElement ? Products.extractPrice(priceElement.textContent) : 5000,
                        rating: Products.extractRating(card) || 5,
                        reviews: Products.extractReviews(card) || Math.floor(Math.random() * 30) + 10
                    };
                    
                    console.log(`Extracted product ${productId}:`, productData);
                    
                    // Update or add to products array
                    const existingIndex = TsubakilFloral.products.findIndex(p => p.id === productId);
                    if (existingIndex === -1) {
                        TsubakilFloral.products.push(productData);
                    } else {
                        TsubakilFloral.products[existingIndex] = { ...TsubakilFloral.products[existingIndex], ...productData };
                    }
                }
            } catch (error) {
                console.warn('Error extracting product data from card:', error);
            }
        });
        
        console.log('Final products array:', TsubakilFloral.products);
    },

    // Extract price from text
    extractPrice: (priceText) => {
        // Remove ¥ symbol and commas, extract numbers
        const match = priceText.replace(/[¥,]/g, '').match(/\d+/);
        return match ? parseInt(match[0]) : 5000;
    },

    // Extract rating from card
    extractRating: (card) => {
        const stars = card.querySelectorAll('.bi-star-fill');
        return stars.length || null;
    },

    // Extract reviews count from card
    extractReviews: (card) => {
        const reviewsElement = card.querySelector('.text-muted small, .small');
        if (reviewsElement) {
            const match = reviewsElement.textContent.match(/\((\d+)\)/);
            return match ? parseInt(match[1]) : null;
        }
        return null;
    },

    // Setup product card interactions
    setupProductCards: () => {
        const productCards = document.querySelectorAll('.product-card, .card');
        console.log(`Setting up ${productCards.length} product cards`);

        productCards.forEach((card, index) => {
            const productId = index + 1;
            
            // Setup add to cart button
            let addToCartBtn = card.querySelector('[data-add-to-cart]');
            if (!addToCartBtn) {
                // Find button by class or create one
                addToCartBtn = card.querySelector('.btn-primary, .btn[class*="primary"], button[class*="cart"]');
                if (addToCartBtn) {
                    addToCartBtn.dataset.addToCart = productId;
                }
            }
            
            // Setup quick view button
            let quickViewBtn = card.querySelector('[data-quick-view]');
            if (!quickViewBtn) {
                // Find existing button or create one
                quickViewBtn = card.querySelector('.btn-light, .btn-outline-primary, .btn-secondary');
                if (quickViewBtn) {
                    quickViewBtn.dataset.quickView = productId;
                } else if (addToCartBtn) {
                    // Create quick view button if it doesn't exist
                    quickViewBtn = document.createElement('button');
                    quickViewBtn.className = 'btn btn-light btn-sm me-2';
                    quickViewBtn.innerHTML = '<i class="bi bi-eye"></i>';
                    quickViewBtn.dataset.quickView = productId;
                    quickViewBtn.title = 'Quick View';
                    
                    // Insert before add to cart button
                    addToCartBtn.parentNode.insertBefore(quickViewBtn, addToCartBtn);
                }
            }
            
            console.log(`Setup product ${productId} - Add to cart: ${!!addToCartBtn}, Quick view: ${!!quickViewBtn}`);
        });
    },

    // Setup quick view functionality
    setupQuickView: () => {
        document.addEventListener('click', (e) => {
            const quickViewBtn = e.target.closest('[data-quick-view]');
            if (quickViewBtn) {
                e.preventDefault();
                e.stopPropagation();
                const productId = parseInt(quickViewBtn.dataset.quickView);
                console.log('Quick view clicked for product:', productId);
                Products.showQuickView(productId);
            }
        });
    },

    // Show product quick view modal
    showQuickView: (productId) => {
        console.log(`Showing quick view for product ID: ${productId}`);
        
        const product = TsubakilFloral.products.find(p => p.id === productId);
        if (!product) {
            console.error(`Product with ID ${productId} not found`);
            Utils.showToast('Product not found', 'warning');
            return;
        }

        console.log('Product found for quick view:', product);

        // Remove existing modal
        const existing = document.getElementById('quickViewModal');
        if (existing) existing.remove();

        // Ensure image URL is valid
        let imageUrl = product.image;
        if (!imageUrl || imageUrl === '' || imageUrl === 'undefined') {
            imageUrl = 'https://via.placeholder.com/400x300/f8f9fa/6c757d?text=Product+Image';
        }

        // Create modal
        const modalHTML = `
            <div class="modal fade" id="quickViewModal" tabindex="-1" aria-labelledby="quickViewModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header border-0">
                            <h5 class="modal-title fw-bold" id="quickViewModalLabel">${product.name}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="product-image-container">
                                        <img src="${imageUrl}" 
                                             class="img-fluid rounded product-modal-image" 
                                             alt="${product.name}"
                                             onerror="this.src='https://via.placeholder.com/400x300/f8f9fa/6c757d?text=Product+Image'">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <p class="text-muted mb-3">${product.description}</p>
                                    <div class="mb-3">
                                        ${Utils.generateStars(product.rating)}
                                        <span class="text-muted ms-2">(${product.reviews} reviews)</span>
                                    </div>
                                    <div class="h4 text-primary mb-4">${Utils.formatPrice(product.price)}</div>
                                    <button class="btn btn-primary btn-lg w-100 mb-2" data-add-to-cart="${product.id}">
                                        <i class="bi bi-bag-plus me-2"></i>Add to Cart
                                    </button>
                                    <div class="row g-2 mt-2">
                                        <div class="col-6">
                                            <button class="btn btn-outline-secondary w-100">
                                                <i class="bi bi-heart me-1"></i>Wishlist
                                            </button>
                                        </div>
                                        <div class="col-6">
                                            <button class="btn btn-outline-secondary w-100">
                                                <i class="bi bi-share me-1"></i>Share
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;

        document.body.insertAdjacentHTML('beforeend', modalHTML);

        const modal = new bootstrap.Modal(document.getElementById('quickViewModal'));
        modal.show();

        // Clean up when hidden
        document.getElementById('quickViewModal').addEventListener('hidden.bs.modal', () => {
            const modalElement = document.getElementById('quickViewModal');
            if (modalElement) {
                modalElement.remove();
            }
        });
    }
};

// =====================================================
// FORM HANDLING
// =====================================================
const Forms = {
    // Initialize forms
    init: () => {
        Forms.setupNewsletter();
        Forms.setupContactForms();
    },
    
    // Newsletter subscription
    setupNewsletter: () => {
        const form = document.getElementById('newsletterForm');
        if (!form) return;
        
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            
            const email = form.querySelector('input[type="email"]').value.trim();
            if (!Forms.validateEmail(email)) {
                Utils.showToast('Please enter a valid email', 'warning');
                return;
            }
            
            const btn = form.querySelector('button[type="submit"]');
            const originalText = btn.textContent;
            
            btn.innerHTML = '<i class="bi bi-arrow-clockwise"></i> Subscribing...';
            btn.disabled = true;
            
            setTimeout(() => {
                btn.innerHTML = originalText;
                btn.disabled = false;
                form.reset();
                Utils.showToast('Successfully subscribed!', 'success');
            }, 1500);
        });
    },
    
    // Contact forms
    setupContactForms: () => {
        document.querySelectorAll('.contact-form').forEach(form => {
            form.addEventListener('submit', (e) => {
                e.preventDefault();
                Forms.handleContact(form);
            });
        });
    },
    
    // Handle contact form
    handleContact: (form) => {
        const data = new FormData(form);
        const fields = Object.fromEntries(data);
        
        if (!fields.name || !fields.email || !fields.message) {
            Utils.showToast('Please fill all required fields', 'warning');
            return;
        }
        
        if (!Forms.validateEmail(fields.email)) {
            Utils.showToast('Please enter a valid email', 'warning');
            return;
        }
        
        const btn = form.querySelector('button[type="submit"]');
        const originalText = btn.textContent;
        
        btn.innerHTML = '<i class="bi bi-arrow-clockwise"></i> Sending...';
        btn.disabled = true;
        
        setTimeout(() => {
            btn.innerHTML = originalText;
            btn.disabled = false;
            form.reset();
            Utils.showToast('Message sent successfully!', 'success');
        }, 2000);
    },
    
    // Email validation
    validateEmail: (email) => {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }
};

// =====================================================
// ANIMATIONS & EFFECTS
// =====================================================
const Animations = {
    // Initialize animations
    init: () => {
        Animations.setupScrollEffects();
        Animations.setupHoverEffects();
        Animations.setupBackToTop();
        Animations.injectCSS();
    },
    
    // Scroll-based animations
    setupScrollEffects: () => {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-in');
                }
            });
        }, { threshold: 0.1 });
        
        // Observe cards and headers
        document.querySelectorAll('.card, h2, h3').forEach(el => {
            el.classList.add('animate-on-scroll');
            observer.observe(el);
        });
    },
    
    // Hover effects for cards
    setupHoverEffects: () => {
        document.querySelectorAll('.card').forEach(card => {
            card.addEventListener('mouseenter', () => {
                card.style.transform = 'translateY(-5px)';
                card.style.boxShadow = '0 8px 25px rgba(0,0,0,0.15)';
            });
            
            card.addEventListener('mouseleave', () => {
                card.style.transform = 'translateY(0)';
                card.style.boxShadow = '';
            });
        });
    },
    
    // Back to top button
    setupBackToTop: () => {
        const existingBtn = document.getElementById('backToTopBtn');
        if (existingBtn) {
            // Show/hide on scroll
            window.addEventListener('scroll', () => {
                if (window.pageYOffset > 300) {
                    existingBtn.classList.remove('d-none');
                } else {
                    existingBtn.classList.add('d-none');
                }
            });
            
            // Scroll to top on click
            existingBtn.addEventListener('click', () => {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        }
    },
    
    // Inject CSS animations
    injectCSS: () => {
        const css = `
            .animate-on-scroll {
                opacity: 0;
                transform: translateY(30px);
                transition: all 0.6s ease;
            }
            .animate-in {
                opacity: 1 !important;
                transform: translateY(0) !important;
            }
            .card {
                transition: all 0.3s ease;
            }
            .product-overlay {
                background: rgba(0,0,0,0.7);
                opacity: 0;
                transition: opacity 0.3s ease;
            }
            .product-card:hover .product-overlay {
                opacity: 1;
            }
            .toast {
                animation: slideInRight 0.3s ease;
            }
            .product-image-container {
                position: relative;
                overflow: hidden;
                border-radius: 0.5rem;
                background: #f8f9fa;
            }
            .product-modal-image {
                width: 100%;
                height: 300px;
                object-fit: cover;
                transition: transform 0.3s ease;
            }
            .product-modal-image:hover {
                transform: scale(1.05);
            }
            @keyframes slideInRight {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
        `;
        
        const style = document.createElement('style');
        style.textContent = css;
        document.head.appendChild(style);
    }
};

// =====================================================
// MOBILE OPTIMIZATION
// =====================================================
const Mobile = {
    // Initialize mobile features
    init: () => {
        Mobile.setupNavigation();
        Mobile.setupTouchFeedback();
    },
    
    // Mobile navigation
    setupNavigation: () => {
        const navCollapse = document.querySelector('.navbar-collapse');
        if (navCollapse) {
            // Close mobile menu when clicking links
            navCollapse.querySelectorAll('.nav-link').forEach(link => {
                link.addEventListener('click', () => {
                    if (window.innerWidth < 992) {
                        const bsCollapse = bootstrap.Collapse.getInstance(navCollapse);
                        if (bsCollapse) bsCollapse.hide();
                    }
                });
            });
        }
    },
    
    // Touch feedback for mobile
    setupTouchFeedback: () => {
        document.querySelectorAll('.btn, .card').forEach(element => {
            element.addEventListener('touchstart', () => {
                element.style.opacity = '0.8';
            });
            element.addEventListener('touchend', () => {
                element.style.opacity = '1';
            });
        });
    }
};

// =====================================================
// APPLICATION INITIALIZATION
// =====================================================
const App = {
    // Initialize application
    init: () => {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', App.start);
        } else {
            App.start();
        }
    },
    
    // Start application
    start: () => {
        try {
            console.log('Initializing TSUBAKI FLORAL application...');
            
            // Initialize all modules
            Cart.init();
            Products.init();
            Forms.init();
            Animations.init();
            Mobile.init();
            
            console.log('Application initialized successfully');
            
        } catch (error) {
            console.error('Initialization error:', error);
            Utils.showToast('Application failed to load properly', 'danger');
        }
    }
};

// =====================================================
// START THE APPLICATION
// =====================================================
App.init();


window.TsubakilFloral = TsubakilFloral;
window.TsubakilFloralApp = { Cart, Products, Forms, Animations, Mobile, Utils };