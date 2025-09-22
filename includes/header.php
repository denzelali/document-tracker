<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>LTFRB Document Management System</title>
  <script src="https://cdn.tailwindcss.com"></script>
  
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            'gov-blue': '#1e3a8a',
            'gov-gold': '#fbbf24',
            'gov-gray': '#374151',
            'gov-light': '#f8fafc'
          },
          fontFamily: {
            'gov': ['Inter', 'system-ui', 'sans-serif']
          }
        }
      }
    }
  </script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Inter', system-ui, sans-serif; }
    
    .gov-seal {
      width: 48px;
      height: 48px;
      background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-weight: 600;
      font-size: 18px;
      box-shadow: 0 4px 12px rgba(30, 64, 175, 0.3);
    }
    
    .dotr-seal {
      width: 48px;
      height: 48px;
      background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-weight: 600;
      font-size: 16px;
      box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
    }
    
    .header-gradient {
      background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 50%, #1e40af 100%);
    }
    
    .glass-effect {
      backdrop-filter: blur(10px);
      background: rgba(255, 255, 255, 0.95);
      border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .search-container {
      position: relative;
      overflow: hidden;
    }
    
    .search-container::before {
      content: '';
      position: absolute;
      inset: 0;
      padding: 1px;
      background: linear-gradient(135deg, #3b82f6, #1e40af);
      border-radius: 12px;
      mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
      mask-composite: exclude;
    }
    
    .btn-primary {
      background: linear-gradient(135deg, #059669 0%, #10b981 100%);
      box-shadow: 0 4px 14px 0 rgba(16, 185, 129, 0.39);
      transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px 0 rgba(16, 185, 129, 0.5);
    }
    
    .official-banner {
      background: linear-gradient(90deg, #1f2937 0%, #374151 50%, #1f2937 100%);
    }
    
    .nav-shadow {
      box-shadow: 0 10px 25px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
  </style>
</head>
<body class="bg-gradient-to-br from-slate-50 to-blue-50 min-h-screen font-gov">

  <!-- Official Government Banner -->
  <div class="official-banner text-white py-3 relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/5 to-transparent"></div>
    <div class="container mx-auto px-6 relative">
      <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">

          <div class="dotr-seal">
    <img src="assets/dotr.png" alt="DOTR Seal">
</div>
          <div class="gov-seal">
    <img src="assets/LTFRB_Seal.png" alt="LTFRB Seal">
</div>


          <div class="flex flex-col">
            <span class="font-semibold text-lg">Land Transportation Franchising and Regulatory Board</span>
            <span class="text-blue-200 text-sm font-light">Department of Transportation</span>
          </div>
        </div>
        <div class="flex items-center space-x-4 text-gray-300">
          <span class="text-sm font-medium">Region X - Northern Mindanao</span>
          <div class="h-6 w-px bg-gray-600"></div>
          <a href="https://www.facebook.com/LTFRBRegionX" target="_blank" class="hover:text-blue-400 transition-colors p-2 rounded-lg hover:bg-white/10">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
              <path d="M22 12a10 10 0 10-11.5 9.9v-7H8v-3h2.5V9.5c0-2.5 1.5-3.9 3.8-3.9 1.1 0 2.2.2 2.2.2v2.5h-1.3c-1.3 0-1.7.8-1.7 1.6V12H18l-.5 3h-2v7A10 10 0 0022 12z"/>
            </svg>
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- Main Navigation -->
  <nav class="glass-effect nav-shadow sticky top-0 z-40" role="navigation" aria-label="Main navigation">
    <div class="container mx-auto px-4 py-4">
      <div class="flex justify-between items-center">
        
        <!-- Logo and Title Section -->
        <div class="flex items-center space-x-4">
          <!-- Back Button with PHP include -->
          <?php include("backbtn.php"); ?>
          
          <div class="h-8 w-px bg-gradient-to-b from-transparent via-gray-300 to-transparent"></div>
          
          <div class="flex flex-col">
            <a href="index.php" class="text-2xl font-bold text-gray-800 tracking-tight hover:text-blue-600 transition-colors">
              Document Management System
            </a>
            <p class="text-gray-600 font-medium text-sm mt-1">
              Government Records & Digital Archive
            </p>
          </div>
        </div>

        <!-- Search and Actions Section -->
        <div class="flex items-center space-x-4">
          
          <!-- Enhanced Search Bar -->
          <div class="search-container">
            <form method="GET" class="relative">
              <div class="relative bg-white rounded-xl overflow-hidden border-2 border-blue-100">
                <input 
                  id="searchInput"
                  type="text" 
                  name="search" 
                  class="bg-white border-0 px-4 py-4 rounded-xl text-sm w-60 focus:outline-none focus:ring-2 focus:ring-blue-500/50 placeholder-gray-500 font-medium"
                  placeholder="Search documents..." 
                  value="">
                
                <!-- Clear button -->
                <button 
                  id="clearBtn"
                  type="button"
                  onclick="clearSearch()"
                  class="absolute right-14 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 p-1 rounded-full hover:bg-gray-100 transition-all hidden">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                  </svg>
                </button>

                <!-- Search button -->
                <button 
                  type="submit" 
                  class="absolute right-2 top-1/2 -translate-y-1/2 bg-gov-blue text-white p-2 rounded-lg hover:bg-blue-800 transition-all group">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1010.5 3a7.5 7.5 0 006.15 13.65z" />
                  </svg>
                </button>
              </div>
            </form>
          </div>

          <!-- New Document Button -->
          <a href="add_document.php" 
             class="btn-primary text-white px-6 py-4 rounded-xl font-semibold hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all group flex items-center space-x-2">
            <svg class="w-5 h-5 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span>New Document</span>
          </a>
        </div>

      </div>
    </div>
  </nav>


  <script>
    // Search functionality
    function toggleClearBtn() {
      const input = document.getElementById('searchInput');
      const clearBtn = document.getElementById('clearBtn');
      if (input.value.trim()) {
        clearBtn.classList.remove('hidden');
      } else {
        clearBtn.classList.add('hidden');
      }
    }

    function clearSearch() {
      const input = document.getElementById('searchInput');
      const clearBtn = document.getElementById('clearBtn');
      input.value = '';
      clearBtn.classList.add('hidden');
      input.focus();
    }

    // Add event listener for search input
    document.getElementById('searchInput').addEventListener('input', toggleClearBtn);

    // Add smooth scrolling for accessibility link
    document.querySelector('a[href="#main-content"]').addEventListener('click', function(e) {
      e.preventDefault();
      document.getElementById('main-content').scrollIntoView({
        behavior: 'smooth'
      });
    });
  </script>

</body>
</html>
