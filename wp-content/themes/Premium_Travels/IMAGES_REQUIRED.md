# Image Assets Required

All external image links have been replaced with WordPress template directory URIs. You need to download the following images from https://www.patratravels.com/ and place them in the theme's `images` folder.

## Required Images Directory
Create this folder: `c:\xampp\htdocs\travel\wp-content\themes\Premium_Travels\images\`

## Images to Download

### Header Images
1. **PatraTravelsLogo.png** - Main logo
   - URL: https://www.patratravels.com/images/PatraTravelsLogo.png
   - Location: `/images/PatraTravelsLogo.png`

2. **whatsapp.png** - WhatsApp icon
   - URL: https://www.patratravels.com/images/whatsapp.png
   - Location: `/images/whatsapp.png`

3. **menu-icon.png** - Mobile menu icon
   - URL: https://www.patratravels.com/images/menu-icon.png
   - Location: `/images/menu-icon.png`

### Front Page Images
4. **taxi.png** - Taxi icon in banner
   - URL: https://www.patratravels.com/images/taxi.png
   - Location: `/images/taxi.png`

5. **default-package.jpg** - Default package thumbnail
   - URL: https://www.patratravels.com/admin/image/tourimage/tourpkgimage_51.jpg
   - Location: `/images/default-package.jpg`

6. **car-honda-city.jpg** - Car rental image
   - URL: https://www.patratravels.com/admin/image/useridntimg/carimg_5.jpg
   - Location: `/images/car-honda-city.jpg`

7. **trst.png** - Why choose us icon
   - URL: https://www.patratravels.com/images/trst.png
   - Location: `/images/trst.png`

8. **customer.png** - Customer icon
   - URL: https://www.patratravels.com/images/customer.png
   - Location: `/images/customer.png`

9. **10years.png** - Experience icon
   - URL: https://www.patratravels.com/images/10years.png
   - Location: `/images/10years.png`

### Footer Images
10. **payment.png** - Payment options
    - URL: https://www.patratravels.com/images/payment.png
    - Location: `/images/payment.png`

11. **payment-accepted-patra-tours-and-travels.png** - Payment methods
    - URL: https://www.patratravels.com/images/payment-accepted-patra-tours-and-travels.png
    - Location: `/images/payment-accepted-patra-tours-and-travels.png`

12. **offline-payment-bank-details-of-patra-tours-and-travels.png** - Bank details
    - URL: https://www.patratravels.com/images/offline-payment-bank-details-of-patra-tours-and-travels.png
    - Location: `/images/offline-payment-bank-details-of-patra-tours-and-travels.png`

13. **ministry-of-tourism-govt-of-india-Patra-Tours-And-Travels.jpg** - Ministry approval
    - URL: https://www.patratravels.com/images/ministry-of-tourism-govt-of-india-Patra-Tours-And-Travels.jpg
    - Location: `/images/ministry-of-tourism-govt-of-india-Patra-Tours-And-Travels.jpg`

14. **Odisha-Tourism-Approved-Tour-Operator-Patra-Tours-And-Travels.jpg** - Odisha Tourism
    - URL: https://www.patratravels.com/images/Odisha-Tourism-Approved-Tour-Operator-Patra-Tours-And-Travels.jpg
    - Location: `/images/Odisha-Tourism-Approved-Tour-Operator-Patra-Tours-And-Travels.jpg`

15. **IATO-Approved-Tour-Operator-Patra-Tours-And-Travels.jpg** - IATO approval
    - URL: https://www.patratravels.com/images/IATO-Approved-Tour-Operator-Patra-Tours-And-Travels.jpg
    - Location: `/images/IATO-Approved-Tour-Operator-Patra-Tours-And-Travels.jpg`

16. **BMC-Approved-Tour-Operator-Patra-Tours-And-Travels.jpg** - BMC approval
    - URL: https://www.patratravels.com/images/BMC-Approved-Tour-Operator-Patra-Tours-And-Travels.jpg
    - Location: `/images/BMC-Approved-Tour-Operator-Patra-Tours-And-Travels.jpg`

17. **EcoTour-Odisha-Approved-Tour-Operator-Patra-Tours-And-Travels.jpg** - EcoTour approval
    - URL: https://www.patratravels.com/images/EcoTour-Odisha-Approved-Tour-Operator-Patra-Tours-And-Travels.jpg
    - Location: `/images/EcoTour-Odisha-Approved-Tour-Operator-Patra-Tours-And-Travels.jpg`

18. **Ektta-Approved-Tour-Operator-Patra-Tours-And-Travels.jpg** - EKTTA approval
    - URL: https://www.patratravels.com/images/Ektta-Approved-Tour-Operator-Patra-Tours-And-Travels.jpg
    - Location: `/images/Ektta-Approved-Tour-Operator-Patra-Tours-And-Travels.jpg`

19. **whatssapp.png** - WhatsApp floating button
    - URL: https://www.patratravels.com/images/whatssapp.png
    - Location: `/images/whatssapp.png`

### Tab Icons (from front-page.php)
These are already using `get_template_directory_uri()` but need to be in the images folder:
- station.png
- island.png
- cruise.png
- round.png
- departure.png
- outstation.png

## Quick Download Script

You can use this PowerShell script to download all images:

```powershell
# Create images directory
New-Item -ItemType Directory -Force -Path "c:\xampp\htdocs\travel\wp-content\themes\Premium_Travels\images"

# Download images
$images = @(
    "PatraTravelsLogo.png",
    "whatsapp.png",
    "menu-icon.png",
    "taxi.png",
    "trst.png",
    "customer.png",
    "10years.png",
    "payment.png",
    "payment-accepted-patra-tours-and-travels.png",
    "offline-payment-bank-details-of-patra-tours-and-travels.png",
    "ministry-of-tourism-govt-of-india-Patra-Tours-And-Travels.jpg",
    "Odisha-Tourism-Approved-Tour-Operator-Patra-Tours-And-Travels.jpg",
    "IATO-Approved-Tour-Operator-Patra-Tours-And-Travels.jpg",
    "BMC-Approved-Tour-Operator-Patra-Tours-And-Travels.jpg",
    "EcoTour-Odisha-Approved-Tour-Operator-Patra-Tours-And-Travels.jpg",
    "Ektta-Approved-Tour-Operator-Patra-Tours-And-Travels.jpg",
    "whatssapp.png"
)

foreach ($img in $images) {
    $url = "https://www.patratravels.com/images/$img"
    $output = "c:\xampp\htdocs\travel\wp-content\themes\Premium_Travels\images\$img"
    Invoke-WebRequest -Uri $url -OutFile $output
    Write-Host "Downloaded: $img"
}

# Download special images with different paths
Invoke-WebRequest -Uri "https://www.patratravels.com/admin/image/tourimage/tourpkgimage_51.jpg" -OutFile "c:\xampp\htdocs\travel\wp-content\themes\Premium_Travels\images\default-package.jpg"
Invoke-WebRequest -Uri "https://www.patratravels.com/admin/image/useridntimg/carimg_5.jpg" -OutFile "c:\xampp\htdocs\travel\wp-content\themes\Premium_Travels\images\car-honda-city.jpg"

Write-Host "All images downloaded successfully!"
```

## After Downloading

Once all images are downloaded:
1. Verify all images are in the `/images/` folder
2. Clear your browser cache
3. Reload the website
4. All images should now load from your local WordPress installation
