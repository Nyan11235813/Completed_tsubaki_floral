/**
 * TSUBAKI FLORAL - Fixed JavaScript
 * Clean, beginner-friendly flower shop website functionality
 */

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
    
    // Sample products
    products: [
        {
            id: 1,
            name: 'Romantic Rose Bouquet',
            price: 10000,
            image: 'assets/images/img6.jpg',
            description: 'Premium red roses with eucalyptus',
            rating: 5,
            reviews: 24
        },
        {
            id: 2,
            name: 'Baby Pink Delight',
            price: 6000,
            image: 'assets/images/img.jpg',
            description: 'Soft pink roses with baby\'s breath',
            rating: 4,
            reviews: 18
        },
        {
            id: 3,
            name: 'Elegant White Lilies',
            price: 7000,
            image: 'assets/images/img8.jpg',
            description: 'Pure white lilies with greenery',
            rating: 5,
            reviews: 31
        },
        {
            id: 4,
            name: 'Tropical Paradise Bouquet',
            price: 8500,
            image: 'assets/images/img9.jpg',
            description: 'Exotic blooms for a fresh and peaceful touch',
            rating: 4,
            reviews: 12
        },
        {
            id: 5,
            name: 'Sunflower Surprise',
            price: 4500,
            image: 'assets/images/img10.jpg',
            description: 'Bright sunflowers with mixed seasonal flowers',
            rating: 5,
            reviews: 28
        },
        {
            id: 6,
            name: 'Orchid Elegance',
            price: 6000,
            image: 'assets/images/img11.jpg',
            description: 'Graceful orchids in a modern pot',
            rating: 5,
            reviews: 19
        }
    ],
    
    // Search suggestions
    searchSuggestions: [
        'roses', 'tulips', 'wedding bouquet', 'anniversary flowers',
        'birthday arrangement', 'lilies', 'orchids', 'seasonal flowers'
    ]
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
            document.body.removeChild(toast);
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
        const product = TsubakilFloral.products.find(p => p.id === productId);
        if (!product) return;
        
        const existingItem = TsubakilFloral.cart.items.find(item => item.id === productId);
        
        if (existingItem) {
            existingItem.quantity += 1;
        } else {
            TsubakilFloral.cart.items.push({ ...product, quantity: 1 });
        }
        
        Cart.updateTotals();
        Cart.updateUI();
        Utils.showToast(`${product.name} added to cart!`);
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
    },
    
    // Bind cart events
    bindEvents: () => {
        document.addEventListener('click', (e) => {
            if (e.target.closest('[data-add-to-cart]')) {
                e.preventDefault();
                const productId = parseInt(e.target.closest('[data-add-to-cart]').dataset.addToCart);
                Cart.addItem(productId);
            }
        });
    }
};

// =====================================================
// SEARCH FUNCTIONALITY
// =====================================================
const Search = {
    // Initialize search
    init: () => {
        Search.bindEvents();
    },
    
    // Bind search events
    bindEvents: () => {
        const searchInput = document.getElementById('searchInput');
        const searchBtn = document.getElementById('searchBtn');
        
        if (searchInput) {
            // Handle search input with debounce
            const debouncedSearch = Utils.debounce(Search.handleInput, 300);
            searchInput.addEventListener('input', debouncedSearch);
            
            // Handle enter key
            searchInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    Search.performSearch(searchInput.value);
                }
            });
            
            // Show suggestions on focus
            searchInput.addEventListener('focus', () => {
                if (searchInput.value.length > 0) {
                    Search.showSuggestions(searchInput.value);
                }
            });
        }
        
        if (searchBtn) {
            searchBtn.addEventListener('click', () => {
                Search.performSearch(searchInput?.value || '');
            });
        }
        
        // Close suggestions when clicking outside
        document.addEventListener('click', (e) => {
            if (!e.target.closest('#searchCollapse')) {
                Search.hideSuggestions();
            }
        });
    },
    
    // Handle search input
    handleInput: (e) => {
        const query = e.target.value.trim();
        if (query.length > 0) {
            Search.showSuggestions(query);
        } else {
            Search.hideSuggestions();
        }
    },
    
    // Show search suggestions
    showSuggestions: (query) => {
        const container = document.getElementById('searchSuggestions');
        if (!container) return;
        
        const filtered = TsubakilFloral.searchSuggestions
            .filter(s => s.toLowerCase().includes(query.toLowerCase()))
            .slice(0, 5);
        
        if (filtered.length > 0) {
            const html = filtered.map(suggestion => 
                `<div class="suggestion-item p-2 border-bottom" data-suggestion="${suggestion}">
                    <i class="bi bi-search me-2"></i>${suggestion}
                </div>`
            ).join('');
            
            container.innerHTML = `<div class="suggestions-list bg-white border rounded shadow-sm mt-1">${html}</div>`;
            
            // Bind suggestion clicks
            container.querySelectorAll('.suggestion-item').forEach(item => {
                item.addEventListener('click', () => {
                    const suggestion = item.dataset.suggestion;
                    document.getElementById('searchInput').value = suggestion;
                    Search.performSearch(suggestion);
                    Search.hideSuggestions();
                });
            });
        }
    },
    
    // Hide suggestions
    hideSuggestions: () => {
        const container = document.getElementById('searchSuggestions');
        if (container) container.innerHTML = '';
    },
    
    // Perform search
    performSearch: (query) => {
        if (!query.trim()) {
            Utils.showToast('Please enter a search term', 'warning');
            return;
        }
        
        Utils.showToast(`Searching for "${query}"...`, 'info');
        Search.hideSuggestions();
        
        // Close search collapse on mobile
        const searchCollapse = document.getElementById('searchCollapse');
        if (searchCollapse) {
            const bsCollapse = bootstrap.Collapse.getInstance(searchCollapse);
            if (bsCollapse) bsCollapse.hide();
        }
    }
};

// =====================================================
// PRODUCT MANAGEMENT
// =====================================================
const Products = {
    // Initialize products
    init: () => {
        Products.setupProductCards();
        Products.setupQuickView();
    },

    // Setup product card interactions
    setupProductCards: () => {
        // Get all product cards and extract data from them
        const cardSelectors = ['.product-card', '.card', '[class*="card"]', '.col .card', '.product', '.item'];
        let cards = [];
        
        // Try different selectors to find product cards
        cardSelectors.forEach(selector => {
            const foundCards = document.querySelectorAll(selector);
            foundCards.forEach(card => {
                // Only add if it contains an image and hasn't been processed
                if (card.querySelector('img') && !card.dataset.processed && !cards.includes(card)) {
                    cards.push(card);
                }
            });
        });

        console.log(`Found ${cards.length} product cards`);

        cards.forEach((card, index) => {
            card.dataset.processed = 'true';
            const productId = index + 1;
            
            // Find and setup add to cart button
            const addToCartBtn = card.querySelector('.btn-primary, .btn[class*="primary"], button[class*="cart"], .add-to-cart');
            if (addToCartBtn && !addToCartBtn.dataset.addToCart) {
                addToCartBtn.dataset.addToCart = productId;
                console.log(`Set up add to cart for product ${productId}`);
            }

            // Find and setup quick view button - try multiple selectors
            const quickViewSelectors = [
                '.btn-light', 
                '.btn-outline-primary', 
                '.btn-secondary', 
                '.btn[class*="light"]',
                '.btn[class*="outline"]',
                'button:not(.btn-primary):not([class*="cart"])',
                '.quick-view',
                '.view-details'
            ];
            
            let quickViewBtn = null;
            for (const selector of quickViewSelectors) {
                quickViewBtn = card.querySelector(selector);
                if (quickViewBtn) break;
            }
            
            // If no specific button found, create one
            if (!quickViewBtn && addToCartBtn) {
                quickViewBtn = document.createElement('button');
                quickViewBtn.className = 'btn btn-outline-primary btn-sm ms-2';
                quickViewBtn.innerHTML = '<i class="bi bi-eye"></i> Quick View';
                addToCartBtn.parentNode.appendChild(quickViewBtn);
            }
            
            if (quickViewBtn && !quickViewBtn.dataset.quickView) {
                quickViewBtn.dataset.quickView = productId;
                console.log(`Set up quick view for product ${productId}`);
                
                // Store product data from HTML for quick view
                Products.extractProductData(card, productId);
            }
        });
    },

    // Extract product data from HTML card
    extractProductData: (card, productId) => {
        try {
            // Try multiple selectors for each element
            const img = card.querySelector('img[src], img[data-src], .card-img-top, .product-image img, .image img');
            const title = card.querySelector('.card-title, h5, h6, h4, h3, .product-title, .title, .name');
            const description = card.querySelector('.card-text, .text-muted, .description, p:not(:empty)');
            const price = card.querySelector('.text-primary, .fw-bold, .price, .cost, [class*="price"]');
            
            console.log(`Extracting data for product ${productId}:`, {
                img: img?.src,
                title: title?.textContent,
                description: description?.textContent,
                price: price?.textContent
            });
            
            // Create product data from HTML
            const productData = {
                id: parseInt(productId),
                name: title ? title.textContent.trim() : `Product ${productId}`,
                image: img ? (img.src || img.dataset.src || img.getAttribute('src')) : '',
                description: description ? description.textContent.trim() : 'Beautiful flower arrangement',
                price: price ? Products.extractPrice(price.textContent) : 5000,
                rating: 5,
                reviews: Math.floor(Math.random() * 30) + 10
            };

            // Fallback image if none found
            if (!productData.image || productData.image === '') {
                productData.image = 'https://via.placeholder.com/400x300/f8f9fa/6c757d?text=Product+Image';
            }

            console.log('Final product data:', productData);

            // Store in products array if not exists
            const existingIndex = TsubakilFloral.products.findIndex(p => p.id === parseInt(productId));
            if (existingIndex === -1) {
                TsubakilFloral.products.push(productData);
            } else {
                // Update existing product with HTML data
                TsubakilFloral.products[existingIndex] = { ...TsubakilFloral.products[existingIndex], ...productData };
            }
        } catch (error) {
            console.warn('Could not extract product data from card:', error);
        }
    },

    // Extract price from text
    extractPrice: (priceText) => {
        const match = priceText.match(/[\d,]+/);
        return match ? parseInt(match[0].replace(/,/g, '')) : 5000;
    },

    // Setup quick view functionality
    setupQuickView: () => {
        document.addEventListener('click', (e) => {
            if (e.target.closest('[data-quick-view]')) {
                e.preventDefault();
                const productId = parseInt(e.target.closest('[data-quick-view]').dataset.quickView);
                Products.showQuickView(productId);
            }
        });
    },

    // Show product quick view modal
    showQuickView: (productId) => {
        console.log(`Showing quick view for product ID: ${productId}`);
        console.log('Available products:', TsubakilFloral.products);
        
        const product = TsubakilFloral.products.find(p => p.id === productId);
        if (!product) {
            console.error(`Product with ID ${productId} not found`);
            Utils.showToast('Product not found', 'warning');
            return;
        }

        console.log('Product found:', product);

        // Remove existing modal
        const existing = document.getElementById('quickViewModal');
        if (existing) existing.remove();

        // Ensure image URL is valid
        let imageUrl = product.image;
        if (!imageUrl || imageUrl === '' || imageUrl === 'undefined') {
            imageUrl = 'https://via.placeholder.com/400x300/f8f9fa/6c757d?text=Product+Image';
        }

        console.log('Using image URL:', imageUrl);

        // Create modal with error handling for image
        const modalHTML = `
            <div class="modal fade" id="quickViewModal" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header border-0">
                            <h5 class="modal-title fw-bold">${product.name}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="product-image-container">
                                        <img src="${imageUrl}" 
                                             class="img-fluid rounded product-modal-image" 
                                             alt="${product.name}"
                                             onload="console.log('Image loaded successfully')"
                                             onerror="console.log('Image failed to load, using fallback'); this.src='https://via.placeholder.com/400x300/f8f9fa/6c757d?text=Product+Image'">
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
            document.getElementById('quickViewModal').remove();
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
        // Create back to top button
        const btn = document.createElement('button');
        btn.id = 'backToTopBtn';
        btn.className = 'back-to-top d-none';
        btn.innerHTML = '<i class="bi bi-arrow-up"></i>';
        btn.setAttribute('aria-label', 'Back to top');
        document.body.appendChild(btn);
        
        // Show/hide on scroll
        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 300) {
                btn.classList.remove('d-none');
            } else {
                btn.classList.add('d-none');
            }
        });
        
        // Scroll to top on click
        btn.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
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
            .back-to-top {
                position: fixed;
                bottom: 20px;
                right: 20px;
                z-index: 1000;
                width: 50px;
                height: 50px;
                border-radius: 50%;
                background: linear-gradient(135deg, #ff6b9d, #c44569);
                border: none;
                color: white;
                font-size: 18px;
                cursor: pointer;
                box-shadow: 0 4px 15px rgba(0,0,0,0.2);
                transition: all 0.3s ease;
            }
            .back-to-top:hover {
                transform: translateY(-3px);
                box-shadow: 0 6px 20px rgba(0,0,0,0.3);
            }
            .suggestion-item {
                cursor: pointer;
                transition: background-color 0.2s ease;
            }
            .suggestion-item:hover {
                background-color: #f8f9fa;
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
            // Initialize all modules
            Cart.init();
            Search.init();
            Products.init();
            Forms.init();
            Animations.init();
            Mobile.init();
            
            // Welcome message
            setTimeout(() => {
                Utils.showToast('Welcome to TSUBAKI FLORAL! 🌸', 'success');
            }, 1000);
            
            console.log('🌸 TSUBAKI FLORAL initialized successfully');
            
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

// Make available globally for debugging
window.TsubakilFloral = TsubakilFloral;
window.TsubakilFloralApp = { Cart, Search, Products, Forms, Animations, Mobile, Utils };