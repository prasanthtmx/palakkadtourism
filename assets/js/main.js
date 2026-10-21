// Main JavaScript for Palakkad Tourism

// Google Analytics 4 Enhanced Tracking
(function() {
    // Track outbound links
    document.addEventListener('click', function(e) {
        const link = e.target.closest('a');
        if (link && link.href) {
            const href = link.href;
            const isExternal = link.hostname && link.hostname !== window.location.hostname;
            const isDownload = /\.(pdf|doc|docx|xls|xlsx|zip|rar|exe)$/i.test(href);
            const isEmail = href.startsWith('mailto:');
            const isPhone = href.startsWith('tel:');
            
            if (isExternal) {
                gtag('event', 'click', {
                    'event_category': 'outbound',
                    'event_label': href,
                    'transport_type': 'beacon',
                    'link_url': href
                });
            } else if (isDownload) {
                gtag('event', 'file_download', {
                    'file_name': href.split('/').pop(),
                    'file_extension': href.split('.').pop(),
                    'link_url': href
                });
            } else if (isEmail) {
                gtag('event', 'email_click', {
                    'email_address': href.replace('mailto:', ''),
                    'link_url': href
                });
            } else if (isPhone) {
                gtag('event', 'phone_click', {
                    'phone_number': href.replace('tel:', ''),
                    'link_url': href
                });
            } else {
                // Internal link tracking
                gtag('event', 'click', {
                    'event_category': 'internal_link',
                    'event_label': link.textContent.trim(),
                    'link_url': href
                });
            }
        }
    });
    
    // Track button clicks
    document.addEventListener('click', function(e) {
        const button = e.target.closest('button');
        if (button) {
            const buttonText = button.textContent.trim() || button.getAttribute('aria-label') || 'Button';
            gtag('event', 'button_click', {
                'button_text': buttonText,
                'button_id': button.id || '',
                'button_class': button.className || ''
            });
        }
    });
    
    // Track form submissions
    document.addEventListener('submit', function(e) {
        const form = e.target;
        if (form.tagName === 'FORM') {
            gtag('event', 'form_submit', {
                'form_id': form.id || '',
                'form_class': form.className || '',
                'form_action': form.action || '',
                'form_method': form.method || 'get'
            });
        }
    });
    
    // Track search queries
    document.addEventListener('submit', function(e) {
        const form = e.target;
        const searchInput = form.querySelector('input[name="search"]');
        if (searchInput && searchInput.value) {
            gtag('event', 'search', {
                'search_term': searchInput.value,
                'page_location': window.location.href
            });
        }
    });
    
    // Track scroll depth
    let maxScroll = 0;
    const scrollThresholds = [25, 50, 75, 90, 100];
    const trackedThresholds = new Set();
    
    window.addEventListener('scroll', function() {
        const scrollPercent = Math.round(
            ((window.scrollY + window.innerHeight) / document.documentElement.scrollHeight) * 100
        );
        
        if (scrollPercent > maxScroll) {
            maxScroll = scrollPercent;
            
            scrollThresholds.forEach(threshold => {
                if (scrollPercent >= threshold && !trackedThresholds.has(threshold)) {
                    trackedThresholds.add(threshold);
                    gtag('event', 'scroll', {
                        'percent_scrolled': threshold
                    });
                }
            });
        }
    });
    
    // Track time on page
    let startTime = Date.now();
    let timeTracked = false;
    
    setInterval(function() {
        const timeOnPage = Math.round((Date.now() - startTime) / 1000);
        
        // Track at 30 seconds, 1 minute, 2 minutes, 5 minutes
        if (!timeTracked && (timeOnPage === 30 || timeOnPage === 60 || timeOnPage === 120 || timeOnPage === 300)) {
            gtag('event', 'timing_complete', {
                'name': 'time_on_page',
                'value': timeOnPage
            });
            if (timeOnPage >= 300) timeTracked = true;
        }
    }, 1000);
    
    // Track dropdown menu interactions
    document.addEventListener('show.bs.dropdown', function(e) {
        const dropdown = e.target.closest('.dropdown');
        if (dropdown) {
            const menuText = dropdown.querySelector('.dropdown-toggle').textContent.trim();
            gtag('event', 'dropdown_open', {
                'dropdown_name': menuText
            });
        }
    });
    
    // Track modal opens
    document.addEventListener('show.bs.modal', function(e) {
        const modal = e.target;
        gtag('event', 'modal_open', {
            'modal_id': modal.id || '',
            'modal_title': modal.querySelector('.modal-title')?.textContent.trim() || ''
        });
    });
    
    // Track pagination clicks
    document.addEventListener('click', function(e) {
        const paginationLink = e.target.closest('.pagination a, .page-link');
        if (paginationLink) {
            gtag('event', 'pagination_click', {
                'page_number': paginationLink.textContent.trim(),
                'link_url': paginationLink.href
            });
        }
    });
    
    // Track filter changes
    document.addEventListener('change', function(e) {
        const select = e.target;
        if (select.tagName === 'SELECT' && (select.name === 'type' || select.name === 'category')) {
            gtag('event', 'filter_change', {
                'filter_name': select.name,
                'filter_value': select.value,
                'page_location': window.location.href
            });
        }
    });
    
    // Track card/item clicks (for listings)
    document.addEventListener('click', function(e) {
        const card = e.target.closest('.card');
        if (card) {
            const cardTitle = card.querySelector('.card-title, h5, h4');
            if (cardTitle) {
                gtag('event', 'card_click', {
                    'card_title': cardTitle.textContent.trim(),
                    'page_location': window.location.href
                });
            }
        }
    });
    
    // Track video plays (if any videos are added later)
    document.addEventListener('play', function(e) {
        if (e.target.tagName === 'VIDEO') {
            gtag('event', 'video_play', {
                'video_title': e.target.getAttribute('title') || e.target.getAttribute('alt') || 'Video',
                'video_url': e.target.src || ''
            });
        }
    }, true);
    
    // Track image views (lazy loaded images)
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    gtag('event', 'image_view', {
                        'image_url': img.src || '',
                        'image_alt': img.alt || ''
                    });
                    imageObserver.unobserve(img);
                }
            });
        });
        
        document.querySelectorAll('img[data-src], img[loading="lazy"]').forEach(img => {
            imageObserver.observe(img);
        });
    }
    
    // Track page visibility changes
    document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
            gtag('event', 'page_hidden', {
                'time_on_page': Math.round((Date.now() - startTime) / 1000)
            });
        } else {
            gtag('event', 'page_visible');
        }
    });
    
    // Track errors
    window.addEventListener('error', function(e) {
        gtag('event', 'exception', {
            'description': e.message,
            'fatal': false
        });
    });
    
    // Track when user leaves page
    window.addEventListener('beforeunload', function() {
        const timeOnPage = Math.round((Date.now() - startTime) / 1000);
        gtag('event', 'page_exit', {
            'time_on_page': timeOnPage,
            'page_path': window.location.pathname
        });
    });
})();

// Weather API Configuration
// Note: Replace 'YOUR_API_KEY' with your OpenWeatherMap API key
// Get free API key from: https://openweathermap.org/api
const WEATHER_API_KEY = 'YOUR_API_KEY'; // Replace with your API key
const WEATHER_CITY = 'Palakkad';
const WEATHER_COUNTRY = 'IN';

// Fetch Weather Data
function fetchWeather() {
    const weatherWidget = document.getElementById('weatherWidget');
    
    if (!weatherWidget) return;
    
    // Using OpenWeatherMap API (free tier available)
    // Alternative: You can also use a free weather widget service
    const apiUrl = `https://api.openweathermap.org/data/2.5/weather?q=${WEATHER_CITY},${WEATHER_COUNTRY}&appid=${WEATHER_API_KEY}&units=metric`;
    
    // If no API key, use a fallback method with a free weather service
    if (WEATHER_API_KEY === 'YOUR_API_KEY') {
        // Using wttr.in as a free alternative (no API key needed)
        fetch(`https://wttr.in/${WEATHER_CITY}?format=j1`)
            .then(response => response.json())
            .then(data => {
                displayWeather(data);
            })
            .catch(error => {
                console.error('Weather fetch error:', error);
                weatherWidget.innerHTML = '<div class="weather-error"><i class="bi bi-exclamation-triangle"></i> Weather unavailable</div>';
            });
    } else {
        // Use OpenWeatherMap API
        fetch(apiUrl)
            .then(response => response.json())
            .then(data => {
                if (data.cod === 200) {
                    displayOpenWeatherMapData(data);
                } else {
                    throw new Error('Weather data not available');
                }
            })
            .catch(error => {
                console.error('Weather fetch error:', error);
                weatherWidget.innerHTML = '<div class="weather-error"><i class="bi bi-exclamation-triangle"></i> Weather unavailable</div>';
            });
    }
}

// Display weather from wttr.in
function displayWeather(data) {
    const weatherWidget = document.getElementById('weatherWidget');
    if (!weatherWidget || !data.current_condition) return;
    
    const current = data.current_condition[0];
    const location = data.nearest_area[0].areaName[0].value;
    
    const temp = current.temp_C;
    const desc = current.weatherDesc[0].value;
    const humidity = current.humidity;
    const windSpeed = current.windspeedKmph;
    
    const weatherIcon = getWeatherIcon(desc, current.is_day === 'yes');
    
    weatherWidget.innerHTML = `
        <div class="weather-content">
            <div class="weather-location">
                <i class="bi bi-geo-alt"></i> ${location}
            </div>
            <div class="weather-icon">${weatherIcon}</div>
            <div class="weather-temp">${temp}°C</div>
            <div class="weather-desc">${desc}</div>
            <div class="weather-details">
                <span><i class="bi bi-droplet"></i> ${humidity}%</span>
                <span><i class="bi bi-wind"></i> ${windSpeed} km/h</span>
            </div>
        </div>
    `;
}

// Display weather from OpenWeatherMap
function displayOpenWeatherMapData(data) {
    const weatherWidget = document.getElementById('weatherWidget');
    if (!weatherWidget) return;
    
    const temp = Math.round(data.main.temp);
    const desc = data.weather[0].description;
    const humidity = data.main.humidity;
    const windSpeed = Math.round(data.wind.speed * 3.6); // Convert m/s to km/h
    const city = data.name;
    
    const weatherIcon = getWeatherIcon(desc, true);
    
    weatherWidget.innerHTML = `
        <div class="weather-content">
            <div class="weather-location">
                <i class="bi bi-geo-alt"></i> ${city}
            </div>
            <div class="weather-icon">${weatherIcon}</div>
            <div class="weather-temp">${temp}°C</div>
            <div class="weather-desc">${desc}</div>
            <div class="weather-details">
                <span><i class="bi bi-droplet"></i> ${humidity}%</span>
                <span><i class="bi bi-wind"></i> ${windSpeed} km/h</span>
            </div>
        </div>
    `;
}

// Get weather icon based on description
function getWeatherIcon(desc, isDay) {
    const descLower = desc.toLowerCase();
    
    if (descLower.includes('sun') || descLower.includes('clear')) {
        return '<i class="bi bi-sun-fill text-warning"></i>';
    } else if (descLower.includes('cloud')) {
        return '<i class="bi bi-cloud-fill text-secondary"></i>';
    } else if (descLower.includes('rain') || descLower.includes('drizzle')) {
        return '<i class="bi bi-cloud-rain-fill text-primary"></i>';
    } else if (descLower.includes('thunder') || descLower.includes('storm')) {
        return '<i class="bi bi-cloud-lightning-rain-fill text-danger"></i>';
    } else if (descLower.includes('mist') || descLower.includes('fog')) {
        return '<i class="bi bi-cloud-fog-fill text-muted"></i>';
    } else if (descLower.includes('snow')) {
        return '<i class="bi bi-snow text-info"></i>';
    } else {
        return '<i class="bi bi-cloud-sun text-secondary"></i>';
    }
}

// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    // Track page load time
    if (window.performance && window.performance.timing) {
        const loadTime = window.performance.timing.loadEventEnd - window.performance.timing.navigationStart;
        if (typeof gtag !== 'undefined') {
            gtag('event', 'page_load_time', {
                'value': loadTime,
                'metric_name': 'load_time',
                'metric_value': loadTime
            });
        }
    }
    
    // Track DOM ready time
    const domReadyTime = Date.now() - (window.performance?.timing?.navigationStart || Date.now());
    if (typeof gtag !== 'undefined') {
        gtag('event', 'dom_ready', {
            'value': domReadyTime
        });
    }
    
    // Load weather data
    fetchWeather();
    
    // Refresh weather every 30 minutes
    setInterval(fetchWeather, 1800000);
    
    // Initialize Bootstrap tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Track tooltip interactions
    tooltipList.forEach(function(tooltip) {
        tooltip._element.addEventListener('shown.bs.tooltip', function() {
            if (typeof gtag !== 'undefined') {
                gtag('event', 'tooltip_view', {
                    'tooltip_text': tooltip._element.getAttribute('data-bs-original-title') || tooltip._element.title
                });
            }
        });
    });

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const href = this.getAttribute('href');
            if (href !== '#' && href.length > 1) {
                e.preventDefault();
                const target = document.querySelector(href);
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            }
        });
    });

    // Add animation on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    // Observe cards for animation
    document.querySelectorAll('.card').forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(card);
    });
});

// Form validation
function validateForm(formId) {
    const form = document.getElementById(formId);
    if (form) {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    }
}

// Image preview function
function previewImage(input, previewId) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById(previewId).src = e.target.result;
            document.getElementById(previewId).style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Confirm delete
function confirmDelete(message) {
    return confirm(message || 'Are you sure you want to delete this item?');
}

// Show loading state
function showLoading(button) {
    if (button) {
        button.disabled = true;
        button.innerHTML = '<span class="loading"></span> Processing...';
    }
}

// Hide loading state
function hideLoading(button, originalText) {
    if (button) {
        button.disabled = false;
        button.innerHTML = originalText || 'Submit';
    }
}

