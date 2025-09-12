<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQs - PetCentre</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Poppins', sans-serif;
        }
        
        .faq-hero {
            background: linear-gradient(rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0.9)), 
                        url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100"><rect width="100" height="100" fill="%23f8fafc"/><path d="M0 0L100 100" stroke="%23e2e8f0" stroke-width="2"/><path d="M100 0L0 100" stroke="%23e2e8f0" stroke-width="2"/></svg>');
            background-size: cover;
        }
        
        .faq-item {
            margin-bottom: 1.5rem;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        
        .faq-item:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
        
        .faq-question {
            background-color: #f8fafc;
            padding: 1.5rem;
            cursor: pointer;
            display: flex;
            justify-content: between;
            align-items: center;
            font-weight: 600;
            border-left: 4px solid #3b82f6;
        }
        
        .faq-answer {
            background-color: white;
            padding: 0 1.5rem;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease, padding 0.3s ease;
        }
        
        .faq-answer.active {
            max-height: 500px;
            padding: 1.5rem;
        }
        
        .faq-icon {
            transition: transform 0.3s ease;
        }
        
        .faq-icon.active {
            transform: rotate(180deg);
        }
        
        .section-title {
            position: relative;
            display: inline-block;
            margin-bottom: 2rem;
        }
        
        .section-title:after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 4px;
            background: linear-gradient(to right, #3b82f6, #2563eb);
            border-radius: 2px;
        }
        
        .search-box {
            position: relative;
            max-width: 500px;
            margin: 0 auto 3rem;
        }
        
        .search-input {
            width: 100%;
            padding: 1rem 1.5rem;
            border-radius: 50px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        
        .search-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        }
        
        .search-icon {
            position: absolute;
            right: 1.5rem;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
        }
        
        .category-filter {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-bottom: 2rem;
        }
        
        .category-btn {
            padding: 0.5rem 1rem;
            border-radius: 50px;
            background-color: #f3f4f6;
            color: #4b5563;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .category-btn:hover, .category-btn.active {
            background-color: #3b82f6;
            color: white;
        }
        
        .no-results {
            text-align: center;
            padding: 2rem;
            background-color: #f9fafb;
            border-radius: 12px;
            margin-top: 2rem;
            display: none;
        }
        
        .back-to-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: #3b82f6;
            color: white;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            transition: all 0.3s ease;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
        }
        
        .back-to-top.visible {
            opacity: 1;
            visibility: visible;
        }
        
        .back-to-top:hover {
            background: #2563eb;
            transform: translateY(-3px);
        }
    </style>
</head>
<body class="bg-white text-gray-800">
    <!-- Hero Section -->
    <section class="faq-hero py-12 md:py-16 px-4">
        <div class="max-w-5xl mx-auto text-center">
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-800 mb-4">Frequently Asked <span class="text-blue-600">Questions</span></h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Find answers to common questions about PetCentre's services, adoption process, and pet care.
            </p>
        </div>
    </section>

    <!-- Search Section -->
    <section class="py-8 bg-gray-50">
        <div class="max-w-4xl mx-auto px-4">
            <div class="search-box">
                <input type="text" id="faqSearch" placeholder="Search FAQs..." class="search-input">
                <div class="search-icon">
                    <i class="fas fa-search"></i>
                </div>
            </div>
            
            <div class="category-filter" id="categoryFilter">
                <button class="category-btn active" data-category="all">All FAQs</button>
                <button class="category-btn" data-category="adoption">Adoption</button>
                <button class="category-btn" data-category="veterinary">Veterinary</button>
                <button class="category-btn" data-category="grooming">Grooming</button>
                <button class="category-btn" data-category="general">General</button>
            </div>
        </div>
    </section>

    <!-- FAQ Content -->
    <section class="py-12 md:py-16 bg-white">
        <div class="max-w-4xl mx-auto px-4">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-6 text-center section-title">Common Questions</h2>
            
            <div class="faq-container">
                <!-- Adoption FAQs -->
                <div class="faq-category" data-category="adoption">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4 mt-8 flex items-center">
                        <i class="fas fa-paw text-blue-600 mr-3"></i> Adoption Questions
                    </h3>
                    
                    <div class="faq-item">
                        <div class="faq-question">
                            <span>How long does the adoption process take?</span>
                            <i class="fas fa-chevron-down faq-icon"></i>
                        </div>
                        <div class="faq-answer">
                            <p class="text-gray-600">The adoption process typically takes 3-5 business days. This includes application review, meeting the pet, and completing the necessary paperwork. We strive to make the process as efficient as possible while ensuring the best match between pets and families.</p>
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <div class="faq-question">
                            <span>What are the requirements to adopt a pet?</span>
                            <i class="fas fa-chevron-down faq-icon"></i>
                        </div>
                        <div class="faq-answer">
                            <p class="text-gray-600">To adopt a pet, you must be at least 21 years old, provide proof of address, and show that you can responsibly care for an animal. We also require references and may conduct a home visit for certain pets. Adoption fees vary based on the animal's age, species, and any medical care they've received.</p>
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <div class="faq-question">
                            <span>Can I return a pet if it doesn't work out?</span>
                            <i class="fas fa-chevron-down faq-icon"></i>
                        </div>
                        <div class="faq-answer">
                            <p class="text-gray-600">Yes, we have a comprehensive return policy. If the adoption doesn't work out, you can return the pet within 30 days for a full refund. After 30 days, we still accept returns but may not provide a refund. Our priority is the well-being of the animal, and we want to ensure every pet is in a suitable home.</p>
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <div class="faq-question">
                            <span>Are all pets vaccinated before adoption?</span>
                            <i class="fas fa-chevron-down faq-icon"></i>
                        </div>
                        <div class="faq-answer">
                            <p class="text-gray-600">Yes, all pets receive age-appropriate vaccinations before adoption. Dogs are vaccinated against rabies, distemper, parvovirus, and adenovirus. Cats are vaccinated against rabies, feline distemper, calicivirus, and herpesvirus. You will receive a complete medical history and vaccination record for your new pet.</p>
                        </div>
                    </div>
                </div>
                
                <!-- Veterinary FAQs -->
                <div class="faq-category" data-category="veterinary">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4 mt-8 flex items-center">
                        <i class="fas fa-stethoscope text-blue-600 mr-3"></i> Veterinary Services
                    </h3>
                    
                    <div class="faq-item">
                        <div class="faq-question">
                            <span>Do you offer emergency veterinary services?</span>
                            <i class="fas fa-chevron-down faq-icon"></i>
                        </div>
                        <div class="faq-answer">
                            <p class="text-gray-600">Yes, we have an on-call veterinarian for emergencies during business hours. After hours, we recommend contacting Animal Emergency Hospital at (555) 123-HELP. For life-threatening emergencies, please proceed to the nearest 24-hour animal emergency facility.</p>
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <div class="faq-question">
                            <span>What veterinary services do you provide?</span>
                            <i class="fas fa-chevron-down faq-icon"></i>
                        </div>
                        <div class="faq-answer">
                            <p class="text-gray-600">We provide comprehensive veterinary services including wellness exams, vaccinations, dental care, spay/neuter surgeries, diagnostic testing, surgical procedures, and specialized care for chronic conditions. Our facility is equipped with digital X-ray, ultrasound, and an in-house laboratory for quick results.</p>
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <div class="faq-question">
                            <span>How often should my pet have a check-up?</span>
                            <i class="fas fa-chevron-down faq-icon"></i>
                        </div>
                        <div class="faq-answer">
                            <p class="text-gray-600">Pets should have a wellness exam at least once a year. Senior pets (7+ years) or pets with chronic health conditions may need check-ups every 6 months. Puppies and kittens require more frequent visits for vaccinations and monitoring during their first year.</p>
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <div class="faq-question">
                            <span>Do you accept pet insurance?</span>
                            <i class="fas fa-chevron-down faq-icon"></i>
                        </div>
                        <div class="faq-answer">
                            <p class="text-gray-600">Yes, we accept all major pet insurance providers. While we don't directly bill insurance companies, we will provide detailed invoices and medical records to help you submit claims for reimbursement. We also offer our own PetCare Wellness Plan for discounted services.</p>
                        </div>
                    </div>
                </div>
                
                <!-- Grooming FAQs -->
                <div class="faq-category" data-category="grooming">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4 mt-8 flex items-center">
                        <i class="fas fa-spa text-blue-600 mr-3"></i> Grooming Services
                    </h3>
                    
                    <div class="faq-item">
                        <div class="faq-question">
                            <span>How often should I groom my pet?</span>
                            <i class="fas fa-chevron-down faq-icon"></i>
                        </div>
                        <div class="faq-answer">
                            <p class="text-gray-600">It depends on the breed and coat type. Generally, dogs should be groomed every 4-6 weeks, while cats may require less frequent grooming. Breeds with longer hair or those that shed heavily may need more frequent grooming. Our experts can provide personalized recommendations based on your pet's specific needs.</p>
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <div class="faq-question">
                            <span>What grooming services do you offer?</span>
                            <i class="fas fa-chevron-down faq-icon"></i>
                        </div>
                        <div class="faq-answer">
                            <p class="text-gray-600">We offer a full range of grooming services including bathing, haircuts, nail trimming, ear cleaning, teeth brushing, and flea/tick treatments. We also offer specialized services like de-shedding treatments, medicated baths, and creative grooming. All grooming is performed by certified professionals in a stress-free environment.</p>
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <div class="faq-question">
                            <span>Do you groom anxious or aggressive pets?</span>
                            <i class="fas fa-chevron-down faq-icon"></i>
                        </div>
                        <div class="faq-answer">
                            <p class="text-gray-600">Yes, we have experienced groomers who specialize in working with anxious or difficult pets. We use gentle techniques and take extra time to ensure your pet's comfort. In some cases, we may recommend sedation grooming performed under veterinary supervision for extremely stressed pets.</p>
                        </div>
                    </div>
                </div>
                
                <!-- General FAQs -->
                <div class="faq-category" data-category="general">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4 mt-8 flex items-center">
                        <i class="fas fa-info-circle text-blue-600 mr-3"></i> General Questions
                    </h3>
                    
                    <div class="faq-item">
                        <div class="faq-question">
                            <span>What are your business hours?</span>
                            <i class="fas fa-chevron-down faq-icon"></i>
                        </div>
                        <div class="faq-answer">
                            <p class="text-gray-600">Our business hours are:<br>
                            Monday - Friday: 8:00 AM - 6:00 PM<br>
                            Saturday: 9:00 AM - 4:00 PM<br>
                            Sunday: 10:00 AM - 2:00 PM<br>
                            Emergency veterinary services may have different hours. Please call ahead for emergency services.</p>
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <div class="faq-question">
                            <span>Do you offer pet training classes?</span>
                            <i class="fas fa-chevron-down faq-icon"></i>
                        </div>
                        <div class="faq-answer">
                            <p class="text-gray-600">Yes, we offer a variety of training classes for dogs of all ages. Our classes include puppy socialization, basic obedience, advanced training, and specialized behavior modification. All training is conducted by certified professional dog trainers using positive reinforcement methods.</p>
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <div class="faq-question">
                            <span>Can I volunteer at PetCentre?</span>
                            <i class="fas fa-chevron-down faq-icon"></i>
                        </div>
                        <div class="faq-answer">
                            <p class="text-gray-600">Yes, we welcome volunteers! Opportunities include animal socialization, administrative support, event assistance, and foster care. All volunteers must complete an application, attend an orientation, and commit to a regular schedule. Visit our website or stop by to learn more about our volunteer program.</p>
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <div class="faq-question">
                            <span>How can I support PetCentre if I can't adopt?</span>
                            <i class="fas fa-chevron-down faq-icon"></i>
                        </div>
                        <div class="faq-answer">
                            <p class="text-gray-600">There are many ways to support us beyond adoption! You can donate supplies from our wish list, make a financial contribution, volunteer your time, become a foster parent, or simply spread the word about our organization. Every bit of support helps us continue our mission of helping pets in need.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="no-results" id="noResults">
                <i class="fas fa-search fa-3x text-gray-300 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">No results found</h3>
                <p class="text-gray-500">Try different keywords or browse by category</p>
            </div>
            
            <div class="mt-12 text-center">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Still have questions?</h3>
                <p class="text-gray-600 mb-6">Contact our team for more information</p>
                <a href="/contact" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 transition">
                    Contact Us <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
    </section>

    <a href="#" class="back-to-top" id="backToTop">
        <i class="fas fa-arrow-up"></i>
    </a>

    <script>
        // Simple FAQ toggle functionality
        document.addEventListener('click', function(e) {
            // FAQ toggle
            if (e.target.closest('.faq-question')) {
                const question = e.target.closest('.faq-question');
                const answer = question.nextElementSibling;
                const icon = question.querySelector('.faq-icon');
                
                // Toggle active class on answer
                answer.classList.toggle('active');
                
                // Toggle active class on icon
                icon.classList.toggle('active');
            }
            
            // Category filter
            if (e.target.closest('.category-btn')) {
                const button = e.target.closest('.category-btn');
                const category = button.dataset.category;
                const categories = document.querySelectorAll('.faq-category');
                const buttons = document.querySelectorAll('.category-btn');
                
                // Update active button
                buttons.forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');
                
                // Show/hide categories
                categories.forEach(cat => {
                    cat.style.display = (category === 'all' || cat.dataset.category === category) ? 'block' : 'none';
                });
                
                // Reset search
                document.getElementById('faqSearch').value = '';
                document.getElementById('noResults').style.display = 'none';
                
                // Show all FAQ items
                document.querySelectorAll('.faq-item').forEach(item => {
                    item.style.display = 'block';
                });
            }
        });
        
        // Search functionality
        document.getElementById('faqSearch').addEventListener('input', function() {
            const term = this.value.toLowerCase();
            const items = document.querySelectorAll('.faq-item');
            let hasResults = false;
            
            items.forEach(item => {
                const question = item.querySelector('.faq-question').textContent.toLowerCase();
                const answer = item.querySelector('.faq-answer').textContent.toLowerCase();
                const isVisible = question.includes(term) || answer.includes(term);
                
                item.style.display = isVisible ? 'block' : 'none';
                if (isVisible) hasResults = true;
            });
            
            // Show no results message if needed
            document.getElementById('noResults').style.display = hasResults ? 'none' : 'block';
        });
        
        // Back to top button
        window.addEventListener('scroll', function() {
            const button = document.getElementById('backToTop');
            button.classList.toggle('visible', window.scrollY > 300);
        });
        
        document.getElementById('backToTop').addEventListener('click', function(e) {
            e.preventDefault();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    </script>
</body>
</html>