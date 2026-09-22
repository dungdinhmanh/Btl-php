# Codex conversation

## User

help me fix the header, it should be look like from tncstore.vn (3 header section header top/mid/bottom), also remove hardcoded product

## Assistant

I’ll inspect the current layout and product data flow, then reshape the header into top/middle/bottom sections modeled on TNC Store and replace any hardcoded product rendering with data-driven content.

## Activity

    $ "C:\\Windows\\System32\\WindowsPowerShell\\v1.0\\powershell.exe" -Command 'Get-ChildItem -Force; rg --files -g "!vendor" -g "!node_modules"'
    status: Completed · exit 0
      
      
          Directory: C:\Users\Admin\Documents\Btl-php
      
      
      Mode                 LastWriteTime         Length Name
      ----                 -------------         ------ ----
      d--h--         9/22/2026   1:26 PM                .git
      d-----         9/18/2026   1:16 PM                assets
      d-----         9/18/2026   3:08 PM                backend
      d-----         9/22/2026   1:26 PM                css
      d-----         9/22/2026  12:53 PM                js
      -a----         9/21/2026   2:32 PM             50 .gitignore
      -a----         9/18/2026   3:00 PM            169 .htaccess
      -a----         9/21/2026   2:32 PM            107 .prettierrc
      -a----         9/18/2026   1:16 PM           2820 404.html
      -a----         9/18/2026   2:59 PM            125 404.php
      -a----         9/18/2026   2:56 PM           7402 about.html
      -a----         9/18/2026   2:59 PM            127 about.php
      -a----         9/18/2026   1:16 PM           7230 admin.html
      -a----         9/18/2026   2:59 PM            127 admin.php
      -a----         9/18/2026   2:56 PM          10762 buildpc.html
      -a----         9/18/2026   2:59 PM            129 buildpc.php
      -a----         9/18/2026   1:16 PM           4180 cart.html
      -a----         9/18/2026   2:59 PM            126 cart.php
      -a----         9/18/2026   2:56 PM           7494 checkout.html
      -a----         9/18/2026   2:59 PM            130 checkout.php
      -a----         9/18/2026   1:16 PM           5325 contact.html
      -a----         9/18/2026   2:59 PM            129 contact.php
      -a----         9/18/2026   1:16 PM           5769 forgot-password.html
      -a----         9/18/2026   2:59 PM            137 forgot-password.php
      -a----         9/22/2026   1:20 PM          27069 index.html
      -a----         9/18/2026   2:59 PM            127 index.php
      -a----          9/7/2026   2:02 PM           1087 LICENSE
      -a----         9/18/2026   1:16 PM           5871 login.html
      -a----         9/18/2026   2:59 PM            127 login.php
      -a----         9/18/2026   2:56 PM           4466 news-post.html
      -a----         9/18/2026   2:59 PM            131 news-post.php
      -a----         9/18/2026   2:56 PM          10089 news.html
      -a----         9/18/2026   2:59 PM            126 news.php
      -a----         9/18/2026   2:56 PM           7977 product-detail.html
      -a----         9/18/2026   2:59 PM            136 product-detail.php
      -a----         9/18/2026   2:56 PM           5572 products.html
      -a----         9/18/2026   2:59 PM            130 products.php
      -a----         9/18/2026   2:56 PM           5928 profile.html
      -a----         9/18/2026   2:59 PM            129 profile.php
      -a----         9/18/2026   1:16 PM           6056 register.html
      -a----         9/18/2026   2:59 PM            130 register.php
      -a----         9/18/2026   1:16 PM           1515 success.html
      -a----         9/18/2026   2:59 PM            129 success.php
      success.php
      contact.html
      success.html
      checkout.php
      register.php
      checkout.html
      cart.php
      register.html
      cart.html
      buildpc.php
      profile.php
      buildpc.html
      profile.html
      products.php
      products.html
      product-detail.php
      product-detail.html
      news.php
      news.html
      news-post.php
      news-post.html
      login.php
      login.html
      LICENSE
      js\products.js
      js\product-detail.js
      js\home.js
      js\header.js
      js\csv-parser.js
      js\checkout.js
      js\cart.js
      js\buildpc.js
      js\admin.js
      js\account.js
      index.php
      index.html
      forgot-password.php
      forgot-password.html
      css\style.css
      contact.php
      backend\views\legacy-page.php
      backend\config\database.php
      backend\bootstrap.php
      backend\auth\register.php
      backend\auth\login.php
      backend\api\products.php
      backend\api\news.php
      backend\src\Support\Http.php
      backend\README.md
      assets\products\vga\url.txt
      assets\products\vga\products.csv
      assets\products\vga\MSI-TX 4080 SUPER.png
      assets\products\vga\msi-rtx-3090-ti-gaming-x-trio.webp
      assets\products\vga\msi-rtx-3070-ventus-3x-oc.webp
      assets\products\vga\MSI-RTX  4060 Ti GAMING.jpg
      assets\products\vga\inno3d-rtx-4070-ti-x3.jpg
      assets\products\vga\gigabyte-rtx-4060-windforce-oc.jpg
      assets\products\vga\gigabyte-rtx-3060-ti-eagle-oc.jpg
      assets\products\vga\gigabyte-RTX 5070 Ti 16GB WINDFORCE.png
      assets\products\vga\gigabyte-RTX 5060 Ti Gaming OC.webp
      assets\products\vga\asus-rtx-3090-tuf-gaming-oc.jpg
      assets\products\vga\asus-rtx-3060-rog-strix-oc.jpg
      assets\products\vga\asus-rog-strix-rtx-3060-ti-oc.png
      assets\products\vga\asus ROG Strix GeForce RTX 4090.webp
      backend\database\schema.sql
      assets\favicon.png
      assets\danh_sach_linh_kien_mau - VGA.csv
      assets\danh_sach_linh_kien_mau - Mainboard.csv
      assets\danh_sach_linh_kien_mau - CPU.csv
      admin.php
      admin.html
      about.php
      about.html
      404.php
      404.html
      assets\products\SSD\url.txt
      assets\products\heatsink\url.txt
      backend\src\Repositories\UserRepository.php
      backend\src\Repositories\ProductRepository.php
      backend\src\Repositories\NewsRepository.php
      assets\products\manifest.csv
      assets\products\ram\url.txt
      assets\products\display\url.txt
      assets\products\psu\url.txt
      assets\products\phụ kiện\url.txt
      assets\products\case\url.txt
      assets\img\category\cat-vga-card-man-hinh-2.png
      assets\img\category\cat-pc-handheld-1.png
      assets\img\category\cat-pc-gaming-miku.jpg
      assets\img\category\cat-pc-do-hoa-asus.jpg
      assets\img\category\cat-man-hinh-may-tinh-1.png
      assets\img\category\cat-laptop-gaming-1.png
      assets\img\category\cat-ghe-gaming-1.png
      assets\img\category\9223-ps5-slim.jpg
      assets\products\mainboard\asus-prime-a520m-k-3.png
      assets\products\mainboard\asus-prime-a520m-k-2.png
      assets\products\mainboard\asus-prime-a520m-k-1.png
      assets\products\mainboard\asus-max-gaming-b840m-ax-csm-3.webp
      assets\products\mainboard\asus-max-gaming-b840m-ax-csm-2.webp
      assets\products\mainboard\asus-max-gaming-b840m-ax-csm-1.webp
      assets\products\mainboard\asus-csm-prime-b860m-k-3.webp
      assets\products\mainboard\asus-csm-prime-b860m-k-2.webp
      assets\products\mainboard\asus-csm-prime-b860m-k-1.webp
      assets\products\mainboard\asus-proart-b650-creator-1.webp
      assets\products\mainboard\asus-prime-a620m-a-3.webp
      assets\products\mainboard\asus-prime-a620m-a-2.webp
      assets\products\mainboard\asus-prime-a620m-a-1.webp
      assets\products\mainboard\asus-proart-b650-creator-3.webp
      assets\products\mainboard\asus-proart-b650-creator-2.webp
      assets\products\mainboard\asus-proart-z890-creator-wifi-1.webp
      assets\products\mainboard\asus-proart-z890-creator-wifi-2.webp
      assets\img\banner\banner-trang-chu-msi-frieren-mobile-1.jpg
      assets\img\banner\banner-trang-chu-asus-t1-pc-2.jpg
      assets\img\banner\banner-trang-chu-asus-hiku.jpg
      assets\img\banner\banner-razer-len-deal-gear-len-doi.png
      assets\img\banner\banner-pc-ai-gigabyte-web-7.jpg
      assets\img\banner\banner-dong-hanh-tro-ve-can-cu-asus-pc.jpg
      assets\img\banner\banner-build-pc-gigabyte-intel-pc.jpg
      assets\img\banner\banner-build-pc-asus-rinh-qua-het-nac.jpg
      assets\img\banner\banner-back-to-school-pc-1.jpg
      assets\img\banner\banner-ad.png
      assets\img\about.jpg
      assets\products\mainboard\gigabyte-b760m-aorus-elite-ax-2.png
      assets\products\mainboard\gigabyte-b760m-aorus-elite-ax-1.png
      assets\products\mainboard\gigabyte-b650m-aorus-elite-ax-3.png
      assets\products\mainboard\gigabyte-b650m-aorus-elite-ax-2.png
      assets\products\mainboard\gigabyte-b650m-aorus-elite-ax-1.png
      assets\products\mainboard\asus-tuf-gaming-z890-plus-wifi-3.webp
      assets\products\mainboard\asus-tuf-gaming-z890-plus-wifi-2.webp
      assets\products\mainboard\asus-tuf-gaming-z890-plus-wifi-1.webp
      assets\products\mainboard\asus-tuf-gaming-x870-plus-wifi-3.webp
      assets\products\mainboard\asus-tuf-gaming-x870-plus-wifi-2.webp
      assets\products\mainboard\asus-tuf-gaming-x870-plus-wifi-1.webp
      assets\products\mainboard\asus-tuf-gaming-b760m-plus-wifi-3.webp
      assets\products\mainboard\asus-tuf-gaming-b760m-plus-wifi-2.webp
      assets\products\mainboard\asus-tuf-gaming-b760m-plus-wifi-1.webp
      assets\products\mainboard\asus-rog-strix-z890-e-gaming-wifi-3.png
      assets\products\mainboard\asus-rog-strix-z890-e-gaming-wifi-2.png
      assets\products\mainboard\asus-rog-strix-z890-e-gaming-wifi-1.png
      assets\products\mainboard\asus-rog-strix-b850-a-gaming-wifi-3.png
      assets\products\mainboard\asus-rog-strix-b850-a-gaming-wifi-2.png
      assets\products\mainboard\asus-rog-strix-b850-a-gaming-wifi-1.png
      assets\products\mainboard\asus-rog-maximus-z890-extreme-wifi-7-3.png
      assets\products\mainboard\asus-rog-maximus-z890-extreme-wifi-7-2.png
      assets\products\mainboard\asus-rog-maximus-z890-extreme-wifi-7-1.png
      assets\products\mainboard\asus-rog-maximus-z890-apex-wifi-7-3.png
      assets\products\mainboard\asus-rog-maximus-z890-apex-wifi-7-2.png
      assets\products\mainboard\asus-rog-maximus-z890-apex-wifi-7-1.png
      assets\products\mainboard\asus-proart-z890-creator-wifi-3.webp
      assets\products\mainboard\msi-mag-b760m-mortar-wifi-3.webp
      assets\products\mainboard\msi-mag-b760m-mortar-wifi-2.webp
      assets\products\mainboard\msi-mag-b760m-mortar-wifi-1.webp
      assets\products\mainboard\msi-mag-b650m-mortar-wifi-3.webp
      assets\products\mainboard\msi-mag-b650m-mortar-wifi-2.webp
      assets\products\mainboard\msi-mag-b650m-mortar-wifi-1.webp
      assets\products\mainboard\gigabyte-x870-aorus-elite-wifi-7-3.png
      assets\products\mainboard\gigabyte-x870-aorus-elite-wifi-7-2.png
      assets\products\mainboard\gigabyte-x870-aorus-elite-wifi-7-1.png
      assets\products\mainboard\gigabyte-b860m-aorus-pro-wifi-7-3.png
      assets\products\mainboard\gigabyte-b860m-aorus-pro-wifi-7-2.png
      assets\products\mainboard\gigabyte-b860m-aorus-pro-wifi-7-1.png
      assets\products\mainboard\gigabyte-b840m-ds3h-wifi-4.png
      assets\products\mainboard\gigabyte-b840m-ds3h-wifi-3.png
      assets\products\mainboard\gigabyte-b840m-ds3h-wifi-2.png
      assets\products\mainboard\gigabyte-b840m-ds3h-wifi-1.png
      assets\products\mainboard\gigabyte-b760m-aorus-elite-ax-3.png
      assets\products\mainboard\msi-meg-z890-godlike-2.webp
      assets\products\mainboard\msi-meg-z890-godlike-1.webp
      assets\products\mainboard\msi-meg-z870e-godlike-3.webp
      assets\products\mainboard\msi-meg-z870e-godlike-2.webp
      assets\products\mainboard\msi-meg-z870e-godlike-1.webp
      assets\products\mainboard\msi-mag-z890-tomahawk-wifi-3.webp
      assets\products\mainboard\msi-mag-z890-tomahawk-wifi-2.webp
      assets\products\mainboard\msi-mag-z890-tomahawk-wifi-1.webp
      assets\products\mainboard\msi-mag-x870-tomahawk-wifi-3.webp
      assets\products\mainboard\msi-mag-x870-tomahawk-wifi-2.webp
      assets\products\mainboard\msi-mag-x870-tomahawk-wifi-1.webp
      assets\products\mainboard\msi-pro-a620m-e-2.webp
      assets\products\mainboard\msi-pro-a620m-e-1.webp
      assets\products\mainboard\msi-mpg-z890-carbon-wifi-3.webp
      assets\products\mainboard\msi-mpg-z890-carbon-wifi-2.webp
      assets\products\mainboard\msi-mpg-z890-carbon-wifi-1.webp
      assets\products\mainboard\msi-meg-z890-godlike-3.webp
      assets\products\mainboard\msi-pro-b760m-a-wifi-2.webp
      assets\products\mainboard\msi-pro-b760m-a-wifi-1.webp
      assets\products\mainboard\msi-pro-a620m-e-3.webp
      assets\products\mainboard\msi-pro-b860m-a-wifi-2.webp
      assets\products\mainboard\msi-pro-b860m-a-wifi-1.webp
      assets\products\mainboard\msi-pro-b760m-a-wifi-3.webp
      assets\products\mainboard\msi-pro-h610m-e-1.webp
      assets\products\mainboard\msi-pro-b860m-a-wifi-3.webp
      assets\products\mainboard\msi-pro-h610m-e-2.webp
      assets\products\mainboard\msi-pro-h610m-e-3.webp
      assets\img\branding\belkin.png
      assets\img\branding\asus.png
      assets\img\branding\asrock.jpg
      assets\img\branding\apple.jpg
      assets\img\branding\apacer.jpg
      assets\img\branding\aoc.png
      assets\img\branding\antec.jpg
      assets\img\branding\amd.jpg
      assets\img\branding\akko.jpg
      assets\img\branding\afox.jpg
      assets\img\branding\aerocool.png
      assets\img\branding\adata.png
      assets\img\branding\acer.png
      assets\products\mainboard\url.txt
      assets\products\mainboard\sources.csv
      assets\products\mainboard\msi-pro-z890-p-wifi-3.webp
      assets\products\mainboard\msi-pro-z890-p-wifi-2.webp
      assets\products\mainboard\msi-pro-z890-p-wifi-1.webp
      assets\img\branding\colorful.jpg
      assets\img\branding\canon.jpg
      assets\img\branding\bykski.jpg
      assets\img\branding\brother.png
      assets\img\branding\boxlight.png
      assets\img\branding\bjx.png
      assets\img\branding\biostar.jpg
      assets\img\branding\benq.jpg
      assets\img\branding\dareu.png
      assets\img\branding\creative.jpg
      assets\img\branding\cougar.png
      assets\img\branding\corsair.png
      assets\img\branding\coolermaster.png
      assets\img\branding\deepcool.jpg
      assets\img\branding\darkflash.jpg
      assets\img\branding\dell.png
      assets\img\branding\dleadtek.jpg
      assets\products\cpu\14700F-2.webp
      assets\products\cpu\14700F-1.webp
      assets\products\cpu\14600KF-2.jpg
      assets\products\cpu\14600KF-1.jpg
      assets\products\cpu\14600K-2.jpg
      assets\products\cpu\14600K-1.webp
      assets\products\cpu\14400F-1.webp
      assets\products\cpu\14100F-2.webp
      assets\products\cpu\14100F-1.webp
      assets\products\cpu\13900K-2.webp
      assets\products\cpu\13900K-1.webp
      assets\products\cpu\13700K-2.webp
      assets\products\cpu\13700K-1.jpg
      assets\products\cpu\13700F-2.jpg
      assets\products\cpu\13700F-1.webp
      assets\products\cpu\13600K-2.webp
      assets\products\cpu\13600K-1.jpg
      assets\products\cpu\13500-2.jpg
      assets\products\cpu\13500-1.webp
      assets\products\cpu\13400F-2.webp
      assets\products\cpu\13400F-1.webp
      assets\products\cpu\13100F-2.webp
      assets\products\cpu\13100F-1.webp
      assets\products\cpu\12900KF-1.jpg
      assets\products\cpu\12900K-2.jpg
      assets\products\cpu\12900K-1.webp
      assets\products\cpu\12700KF-2.webp
      assets\products\cpu\12700KF-1.jpg
      assets\products\cpu\12400F-2.webp
      assets\products\cpu\12400F-1.webp
      assets\products\cpu\12100F-2.webp
      assets\products\cpu\12100F-1.jpg
      assets\img\branding\dlink.png
      assets\products\cpu\5500-1.jpg
      assets\products\cpu\4500-2.webp
      assets\products\cpu\4500-1.jpg
      assets\products\cpu\4100-2.webp
      assets\products\cpu\4100-1.jpg
      assets\products\cpu\285K-2.jpg
      assets\products\cpu\285K-1.jpg
      assets\products\cpu\265KF-2.webp
      assets\products\cpu\265KF-1.jpg
      assets\products\cpu\265K-2.jpg
      assets\products\cpu\265K-1.jpg
      assets\products\cpu\245KF-2.jpg
      assets\products\cpu\245KF-1.jpg
      assets\products\cpu\245K-2.webp
      assets\products\cpu\245K-1.webp
      assets\products\cpu\14900KS-2.png
      assets\products\cpu\14900KS-1.jpg
      assets\products\cpu\14700K-2.webp
      assets\products\cpu\14700K-1.webp
      assets\img\branding\dxracer.png
      assets\products\cpu\5800X3D-1.jpg
      assets\products\cpu\5700X3D-2.webp
      assets\products\cpu\5700X3D-1.jpg
      assets\products\cpu\5700X-2.webp
      assets\products\cpu\5700X-1.jpg
      assets\products\cpu\5600X-2.jpg
      assets\products\cpu\5600X-1.jpg
      assets\products\cpu\5600-2.webp
      assets\products\cpu\5600-1.jpg
      assets\products\cpu\5500-2.jpg
      assets\products\cpu\7600X-2.webp
      assets\products\cpu\7600X-1.jpg
      assets\products\cpu\7600-2.webp
      assets\products\cpu\7600-1.jpg
      assets\products\cpu\5800X3D-2.jpg
      assets\products\cpu\7700X-1.jpg
      assets\products\cpu\7700-2.jpg
      assets\products\cpu\7700-1.jpg
      assets\img\branding\e-blue.jpg
      assets\products\cpu\7800X3D-1.jpg
      assets\products\cpu\7700X-2.jpg
      assets\products\cpu\7800X3D-2.jpg
      assets\products\cpu\sources.csv
      assets\products\cpu\9950X-2.jpg
      assets\products\cpu\9950X-1.jpg
      assets\products\cpu\9900X-2.webp
      assets\products\cpu\9900X-1.jpg
      assets\products\cpu\9700X-2.jpg
      assets\products\cpu\9700X-1.jpg
      assets\products\cpu\9600X-2.jpg
      assets\products\cpu\9600X-1.jpg
      assets\products\cpu\8700G-2.webp
      assets\products\cpu\8700G-1.jpg
      assets\products\cpu\8600G-2.webp
      assets\products\cpu\8600G-1.jpg
      assets\products\cpu\8400F-2.webp
      assets\products\cpu\8400F-1.jpg
      assets\products\cpu\7950X-2.jpg
      assets\products\cpu\7950X-1.jpg
      assets\products\cpu\7900X3D-2.webp
      assets\products\cpu\7900X3D-1.jpg
      assets\products\cpu\7900X-2.jpg
      assets\products\cpu\7900X-1.jpg
      assets\products\cpu\7900-2.webp
      assets\products\cpu\7900-1.jpg
      assets\img\branding\gigabyte.png
      assets\img\branding\geezer.jpg
      assets\img\branding\gamemax.jpg
      assets\img\branding\gamdias.jpg
      assets\img\branding\galax.png
      assets\img\branding\fuhlen.jpg
      assets\img\branding\fractal.jpg
      assets\img\branding\forgame.png
      assets\img\branding\fl-esports.jpg
      assets\img\branding\filco.png
      assets\img\branding\epson.png
      assets\img\branding\epione.png
      assets\img\branding\elgato.png
      assets\img\branding\edra.jpg
      assets\img\branding\edifier.jpg
      assets\img\branding\hytic.jpg
      assets\img\branding\hyperx.jpg
      assets\img\branding\hyperwork.png
      assets\img\branding\humanmotion.jpg
      assets\img\branding\hp.png
      assets\img\branding\hkc.png
      assets\img\branding\hbada.jpg
      assets\img\branding\gskill.jpg
      assets\img\branding\jabra.jpg
      assets\img\branding\intel.jpg
      assets\img\branding\inno3d.jpg
      assets\img\branding\id-cooling.png
      assets\img\branding\kenoo.png
      assets\img\branding\kaspersky.jpg
      assets\img\branding\keychron.png
      assets\img\branding\kimtigo.jpg
      assets\img\Banner KM\anh4.jpg
      assets\img\Banner KM\anh3.jpg
      assets\img\Banner KM\anh2.jpg
      assets\img\Banner KM\anh1.jpg
      assets\img\branding\logitech.png
      assets\img\branding\lofree.jpg
      assets\img\branding\liteon.jpg
      assets\img\branding\linksys.jpg
      assets\img\branding\lianli.png
      assets\img\branding\lg.png
      assets\img\branding\lexar.png
      assets\img\branding\leopold.jpg
      assets\img\branding\lenovo.png
      assets\img\branding\legion.png
      assets\img\branding\klevv.jpg
      assets\img\branding\kingston.png
      assets\img\branding\kingston-big.png
      assets\img\branding\noctua.png
      assets\img\branding\newmen.jpg
      assets\img\branding\msi.png
      assets\img\branding\montech.png
      assets\img\branding\mik.png
      assets\img\branding\microsoft.jpg
      assets\img\branding\microlab.jpg
      assets\img\branding\orico.jpg
      assets\img\branding\omanli.jpg
      assets\img\branding\ocpc.png
      assets\img\branding\nzxt.png
      assets\img\branding\panasonic.jpg
      assets\img\branding\palit.png
      assets\img\branding\philips.png
      assets\img\branding\sandisk.jpg
      assets\img\branding\sihoo.png
      assets\img\branding\skhynix.png
      assets\img\branding\razer.png
      assets\img\branding\samsung.png
      assets\img\branding\sades.png
      assets\img\branding\ruijie.png
      assets\img\branding\ruijie-2.png
      assets\img\branding\roccat.jpg
      assets\img\branding\seagate.jpg
      assets\img\branding\sapphire.jpg
      assets\img\branding\santak.jpg
      assets\img\branding\senheiser.png
      assets\img\branding\seagate.png
      assets\img\branding\sharkoon.jpg
      assets\img\branding\siliconpower.png
      assets\img\branding\silverstone.jpg
      assets\img\branding\tecware.jpg
      assets\img\branding\teamgroup.png
      assets\img\branding\steelseries.png
      assets\img\branding\sony.png
      assets\img\branding\sony-2.png
      assets\img\branding\quadro.jpg
      assets\img\branding\plextor.jpg
      assets\img\branding\thermaltake.jpg
      assets\img\branding\thermalright.jpg
      assets\img\branding\pioneer.png
      assets\img\branding\thronmax.png
      assets\img\branding\tnc.png
      assets\img\branding\raidmax.jpg
      assets\img\branding\rapoo.png
      assets\img\branding\tncbusiness.png
      assets\img\branding\toshiba.jpg
      assets\img\branding\zotac.png
      assets\img\branding\zidli.png
      assets\img\branding\zadez.png
      assets\img\branding\xigmatek.jpg
      assets\img\branding\xiaomi.png
      assets\img\branding\wd.jpg
      assets\img\branding\vsp.png
      assets\img\branding\vive.png
      assets\img\branding\vitra.jpg
      assets\img\branding\viewsonics.png
      assets\img\branding\viewsonic.png
      assets\img\branding\varmilo.jpg
      assets\img\branding\v-color.jpg
      assets\img\branding\ugreen.jpg
      assets\img\branding\tp-link.jpg
      
      

## Activity

    web search: https://www.tncstore.vn/

## Activity

    $ "C:\\Windows\\System32\\WindowsPowerShell\\v1.0\\powershell.exe" -Command 'rg -n -i "header|product|products|hardcode|mock|sample|featured" index.html products.html product-detail.html css/style.css js/home.js js/products.js js/product-detail.js js/header.js backend/api/products.php backend/src/Repositories/ProductRepository.php backend/database/schema.sql'
    status: Completed · exit 0
      index.html:38:		<div class="header">
      index.html:39:			<div class="header-top">
      index.html:51:			<div class="header-mid container-fluid">
      index.html:83:									<a class="dropdown-item" href="products.html?category=pc">
      index.html:88:									<a class="dropdown-item" href="products.html?category=keyboard">
      index.html:93:									<a class="dropdown-item" href="products.html?category=monitor">
      index.html:100:										href="products.html?category=gaming-gear"
      index.html:110:							action="products.html"
      index.html:113:							<label class="visually-hidden" for="site-search">Search products</label>
      index.html:126:						<div class="header-actions ms-lg-auto">
      index.html:128:								class="header-action header-button"
      index.html:136:							<a class="header-action cart-action" href="cart.html">
      index.html:147:			<div class="header-bottom">
      index.html:149:					<div class="header-menu">
      index.html:304:					<h2 class="category-featured-title">Danh mục nổi bật</h2>
      index.html:306:						<a href="products.html?category=pc" class="category-tile">
      index.html:313:						<a href="products.html?category=pc" class="category-tile">
      index.html:323:						<a href="products.html?category=monitor" class="category-tile">
      index.html:333:						<a href="products.html?category=pc" class="category-tile">
      index.html:343:						<a href="products.html?category=pc" class="category-tile">
      index.html:353:						<a href="products.html?category=gaming-gear" class="category-tile">
      index.html:363:						<a href="products.html?category=gaming-gear" class="category-tile">
      index.html:373:						<a href="products.html?category=gaming-gear" class="category-tile">
      index.html:390:						<a href="products.html" class="text-link">
      index.html:395:					<div class="row g-4" id="featured-products-list">
      index.html:397:							<article class="product-card d-flex w-100 flex-column">
      index.html:398:								<div class="product-image">
      index.html:400:									<span class="product-tag">Bán chạy</span>
      index.html:402:								<p class="product-brand">Intel · Socket LGA1700</p>
      index.html:405:										href="product-detail.html?model=Core%20i5-12400F"
      index.html:411:								<strong class="product-price">3.200.000 đ</strong>
      index.html:605:					<div class="modal-header border-0 pb-0">
      index.html:778:						<a href="products.html">Sản phẩm</a>
      index.html:813:		<script src="js/header.js"></script>
      js/header.js:3:        document.querySelector(".header").classList.add("header-fixed");
      js/header.js:5:        document.querySelector(".header").classList.remove("header-fixed");
      product-detail.html:19:		<header class="site-header">
      product-detail.html:52:									<a class="dropdown-item" href="products.html?category=pc">
      product-detail.html:57:									<a class="dropdown-item" href="products.html?category=keyboard">
      product-detail.html:62:									<a class="dropdown-item" href="products.html?category=monitor">
      product-detail.html:69:										href="products.html?category=gaming-gear"
      product-detail.html:79:							action="products.html"
      product-detail.html:82:							<label class="visually-hidden" for="site-search">Search products</label>
      product-detail.html:95:						<div class="header-actions ms-lg-auto">
      product-detail.html:96:							<a class="header-action header-button" href="index.html#accountModal">
      product-detail.html:100:							<a class="header-action cart-action" href="cart.html">
      product-detail.html:111:		</header>
      product-detail.html:120:							<a href="products.html">Sản phẩm</a>
      product-detail.html:124:							id="breadcrumb-product-name"
      product-detail.html:137:							<div class="mb-3 text-center product-image-zoom">
      product-detail.html:139:									id="main-product-img"
      product-detail.html:142:									class="product-gallery-main"
      product-detail.html:155:								id="product-brand"
      product-detail.html:157:							<h1 class="h3 fw-bold mb-2" id="product-name">Đang tải sản phẩm...</h1>
      product-detail.html:158:							<p class="text-muted mb-3" id="product-model"></p>
      product-detail.html:160:							<h2 class="h3 fw-bold mb-3" style="color: #d32f2f" id="product-price">
      product-detail.html:164:							<p class="text-secondary mb-4" id="product-desc">
      product-detail.html:170:								<label class="form-label fw-semibold" for="product-qty">
      product-detail.html:175:									id="product-qty"
      product-detail.html:204:					<table class="table table-bordered mb-0 product-spec-table" id="spec-table">
      product-detail.html:227:						<a href="products.html">Sản phẩm</a>
      product-detail.html:256:		<script src="js/product-detail.js"></script>
      js/home.js:6:	const container = document.querySelector("#featured-products-list");
      js/home.js:13:	// MySQL-backed products take priority once the database is configured.
      js/home.js:17:		const response = await fetch(`${apiBase}/products.php?featured=1&limit=4`);
      js/home.js:21:				.map((product) => {
      js/home.js:22:					const name = escapeHtml(product.name);
      js/home.js:23:					const brand = escapeHtml(product.brand || "TNC Store");
      js/home.js:24:					const socket = escapeHtml(product.socket || "Liên hệ");
      js/home.js:25:					const image = escapeHtml(product.image || "assets/img/branding/tnc.png");
      js/home.js:26:					const url = `product-detail.php?slug=${encodeURIComponent(product.slug)}`;
      js/home.js:27:					const price = new Intl.NumberFormat("vi-VN").format(Number(product.price) || 0);
      js/home.js:28:					return `<div class="col-sm-6 col-lg-3 d-flex"><article class="product-card d-flex w-100 flex-column"><a href="${url}" class="product-image p-3 text-center bg-white d-block text-decoration-none"><img src="${image}" alt="${name}" class="img-fluid" style="height: 160px; object-fit: contain;"><span class="product-tag">Bán chạy</span></a><p class="product-brand">${brand} · ${socket}</p><h3><a href="${url}" class="text-decoration-none text-dark">${name}</a></h3><strong class="product-price">${price} đ</strong><button class="btn btn-outline-primary w-100 mt-auto" type="button"><i class="bi bi-cart-plus me-2"></i>Thêm vào giỏ</button></article></div>`;
      js/home.js:37:	const cpuList = await fetchAndParseCSV("assets/products/cpu/sources.csv");
      js/home.js:41:	const featured = cpuList.filter((p) => p.Ảnh && p.Ảnh.trim().length > 0).slice(0, 4);
      js/home.js:43:	container.innerHTML = featured
      js/home.js:46:			const imgSrc = `assets/products/cpu/${firstImg}`;
      js/home.js:47:			const detailUrl = `product-detail.html?model=${encodeURIComponent(p.Model)}`;
      js/home.js:53:					<article class="product-card d-flex w-100 flex-column">
      js/home.js:54:						<a href="${detailUrl}" class="product-image p-3 text-center bg-white d-block text-decoration-none">
      js/home.js:56:							<span class="product-tag">Bán chạy</span>
      js/home.js:58:						<p class="product-brand">${p.Hãng} · Socket ${p.Socket}</p>
      js/home.js:62:						<strong class="product-price">${price}</strong>
      css/style.css:2:	--header-ink: #17212b;
      css/style.css:3:	--header-accent: #29324e;
      css/style.css:4:	--header-border: #e8ebee;
      css/style.css:14:	color: var(--header-ink);
      css/style.css:47:	--bs-btn-bg: var(--header-accent);
      css/style.css:48:	--bs-btn-border-color: var(--header-accent);
      css/style.css:53:	--bs-btn-disabled-bg: var(--header-accent);
      css/style.css:54:	--bs-btn-disabled-border-color: var(--header-accent);
      css/style.css:57:	--bs-btn-color: var(--header-accent);
      css/style.css:58:	--bs-btn-border-color: var(--header-accent);
      css/style.css:59:	--bs-btn-hover-bg: var(--header-accent);
      css/style.css:60:	--bs-btn-hover-border-color: var(--header-accent);
      css/style.css:62:	--bs-btn-active-bg: var(--header-accent);
      css/style.css:63:	--bs-btn-active-border-color: var(--header-accent);
      css/style.css:67:	--bs-btn-color: var(--header-accent);
      css/style.css:68:	color: var(--header-accent);
      css/style.css:89:/* Header */
      css/style.css:90:.header {
      css/style.css:94:.header .header-fixed {
      css/style.css:99:.header .header-top {
      css/style.css:104:.header .header-top .item {
      css/style.css:108:.header .header-top .item:last-child {
      css/style.css:112:.header .header-top .item i {
      css/style.css:116:.header .header-top .item span {
      css/style.css:120:.header .header-mid {
      css/style.css:124:.header .container {
      css/style.css:127:.header .header-bottom {
      css/style.css:134:	color: var(--header-ink);
      css/style.css:143:	color: var(--header-accent);
      css/style.css:168:	color: var(--header-ink);
      css/style.css:177:	border-color: var(--header-accent);
      css/style.css:179:	color: var(--header-accent);
      css/style.css:186:	border: 1px solid var(--header-border);
      css/style.css:194:	color: var(--header-ink);
      css/style.css:201:	color: var(--header-accent);
      css/style.css:213:	border-color: var(--header-accent);
      css/style.css:225:	background: var(--header-accent);
      css/style.css:230:.header-actions {
      css/style.css:236:.header-action {
      css/style.css:248:.header-button {
      css/style.css:254:.header-action > i,
      css/style.css:260:.header-action small {
      css/style.css:349:	color: var(--header-accent);
      css/style.css:369:	color: var(--header-ink);
      css/style.css:404:.category-featured-title {
      css/style.css:408:.product-card {
      css/style.css:414:.product-card:hover {
      css/style.css:418:.product-card .btn {
      css/style.css:421:.product-image {
      css/style.css:430:.product-image-alt {
      css/style.css:434:.product-image-dark {
      css/style.css:438:.product-image-green {
      css/style.css:442:.product-tag {
      css/style.css:447:	background: var(--header-accent);
      css/style.css:453:.product-brand {
      css/style.css:460:.product-card h3 {
      css/style.css:465:.product-price {
      css/style.css:466:	color: var(--header-accent);
      css/style.css:483:	color: var(--header-accent);
      css/style.css:493:	color: var(--header-ink);
      css/style.css:536:	color: var(--header-ink);
      css/style.css:548:	background: var(--header-accent);
      css/style.css:574:	color: var(--header-accent);
      css/style.css:582:	color: var(--header-ink);
      css/style.css:588:	background: var(--header-accent);
      css/style.css:734:	color: var(--header-accent);
      css/style.css:777:	color: var(--header-ink);
      css/style.css:786:	border-color: var(--header-accent);
      css/style.css:798:	color: var(--header-accent);
      css/style.css:854:	color: var(--header-accent);
      css/style.css:872:	color: var(--header-accent);
      css/style.css:908:	color: var(--header-ink);
      css/style.css:928:	color: var(--header-accent);
      css/style.css:945:.part-picker-modal .modal-header {
      css/style.css:976:	border-color: var(--header-accent);
      css/style.css:985:	color: var(--header-ink);
      css/style.css:1044:	border-top: 2px solid var(--header-accent);
      css/style.css:1048:	color: var(--header-accent);
      css/style.css:1079:	border-bottom: 2px solid var(--header-accent);
      css/style.css:1080:	color: var(--header-accent);
      css/style.css:1100:	border: 1px solid var(--header-border);
      css/style.css:1111:	color: var(--header-accent);
      css/style.css:1154:	accent-color: var(--header-accent);
      css/style.css:1179:.cart-product-image {
      css/style.css:1228:.summary-card > div:not(.mini-product):not([data-checkout-items]) {
      css/style.css:1257:	color: var(--header-ink);
      css/style.css:1261:	color: var(--header-accent);
      css/style.css:1273:	color: var(--header-accent);
      css/style.css:1283:	color: var(--header-accent);
      css/style.css:1318:	border-color: var(--header-accent);
      css/style.css:1319:	box-shadow: 0 0 0 1px var(--header-accent);
      css/style.css:1322:	accent-color: var(--header-accent);
      css/style.css:1345:	color: var(--header-accent);
      css/style.css:1349:.mini-product {
      css/style.css:1357:.mini-product i {
      css/style.css:1359:	color: var(--header-accent);
      css/style.css:1361:.mini-product b {
      css/style.css:1402:	background: var(--header-accent);
      css/style.css:1436:	color: var(--header-accent);
      css/style.css:1446:	border-top: 2px solid var(--header-accent);
      css/style.css:1449:	color: var(--header-accent);
      css/style.css:1490:	color: var(--header-accent);
      css/style.css:1498:	color: var(--header-accent);
      css/style.css:1552:	background: var(--header-accent);
      css/style.css:1681:	color: var(--header-accent);
      css/style.css:1707:	color: var(--header-accent);
      css/style.css:1804:.stock-product {
      css/style.css:1809:.stock-product > i {
      css/style.css:1810:	color: var(--header-accent);
      css/style.css:1813:.stock-product span,
      css/style.css:1814:.stock-product small {
      css/style.css:1817:.stock-product span {
      css/style.css:1820:.stock-product small {
      css/style.css:1873:	background: var(--header-accent);
      css/style.css:1880:	.header-actions {
      css/style.css:1899:	.header-actions {
      css/style.css:1904:	.header-action {
      css/style.css:1955:/* ── Product Detail Page ── */
      css/style.css:1956:.product-gallery-main {
      css/style.css:1962:	border: 1px solid var(--header-border);
      css/style.css:1965:.product-image-zoom {
      css/style.css:1972:.product-image-zoom .product-gallery-main {
      css/style.css:1976:.product-image-zoom:hover .product-gallery-main {
      css/style.css:1980:	.product-image-zoom {
      css/style.css:1984:.product-thumb {
      css/style.css:1995:.product-thumb.active,
      css/style.css:1996:.product-thumb:hover {
      css/style.css:1997:	border-color: var(--header-accent);
      css/style.css:1999:.product-spec-table th {
      css/style.css:2118:	color: var(--header-ink);
      css/style.css:2145:	color: var(--header-accent);
      css/style.css:2180:.news-page-header {
      css/style.css:2184:.news-page-header h1 {
      css/style.css:2188:.news-page-header p {
      css/style.css:2209:	border-color: var(--header-accent);
      css/style.css:2210:	background: var(--header-accent);
      css/style.css:2277:	color: var(--header-ink);
      backend/database/schema.sql:3:-- This file creates structure and small lookup data only; it contains no sample customers or orders.
      backend/database/schema.sql:73:CREATE TABLE products (
      backend/database/schema.sql:74:  product_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
      backend/database/schema.sql:78:  product_slug VARCHAR(180) NOT NULL,
      backend/database/schema.sql:79:  product_name VARCHAR(255) NOT NULL,
      backend/database/schema.sql:82:  product_status ENUM('draft', 'active', 'archived') NOT NULL DEFAULT 'draft',
      backend/database/schema.sql:83:  is_featured BOOLEAN NOT NULL DEFAULT FALSE,
      backend/database/schema.sql:86:  CONSTRAINT uq_products_sku UNIQUE (sku),
      backend/database/schema.sql:87:  CONSTRAINT uq_products_slug UNIQUE (product_slug),
      backend/database/schema.sql:88:  CONSTRAINT fk_products_brand FOREIGN KEY (brand_id) REFERENCES brands (brand_id) ON DELETE SET NULL,
      backend/database/schema.sql:89:  CONSTRAINT fk_products_category FOREIGN KEY (category_id) REFERENCES categories (category_id),
      backend/database/schema.sql:90:  CONSTRAINT chk_products_name CHECK (CHAR_LENGTH(TRIM(product_name)) >= 1),
      backend/database/schema.sql:91:  CONSTRAINT chk_products_price CHECK (unit_price >= 0)
      backend/database/schema.sql:93:CREATE INDEX ix_products_catalog ON products (product_status, is_featured, created_at);
      backend/database/schema.sql:94:CREATE INDEX ix_products_category ON products (category_id);
      backend/database/schema.sql:95:CREATE INDEX ix_products_brand ON products (brand_id);
      backend/database/schema.sql:97:CREATE TABLE product_images (
      backend/database/schema.sql:98:  product_image_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
      backend/database/schema.sql:99:  product_id BIGINT UNSIGNED NOT NULL,
      backend/database/schema.sql:103:  CONSTRAINT uq_product_images_order UNIQUE (product_id, display_order),
      backend/database/schema.sql:104:  CONSTRAINT fk_product_images_product FOREIGN KEY (product_id)
      backend/database/schema.sql:105:    REFERENCES products (product_id) ON DELETE CASCADE
      backend/database/schema.sql:116:CREATE TABLE product_specifications (
      backend/database/schema.sql:117:  product_id BIGINT UNSIGNED NOT NULL,
      backend/database/schema.sql:120:  PRIMARY KEY (product_id, specification_id),
      backend/database/schema.sql:121:  CONSTRAINT fk_product_specs_product FOREIGN KEY (product_id)
      backend/database/schema.sql:122:    REFERENCES products (product_id) ON DELETE CASCADE,
      backend/database/schema.sql:123:  CONSTRAINT fk_product_specs_definition FOREIGN KEY (specification_id)
      backend/database/schema.sql:136:  product_id BIGINT UNSIGNED NOT NULL,
      backend/database/schema.sql:139:  PRIMARY KEY (warehouse_id, product_id),
      backend/database/schema.sql:142:  CONSTRAINT fk_inventory_product FOREIGN KEY (product_id)
      backend/database/schema.sql:143:    REFERENCES products (product_id) ON DELETE CASCADE,
      backend/database/schema.sql:162:  product_id BIGINT UNSIGNED NOT NULL,
      backend/database/schema.sql:164:  PRIMARY KEY (cart_id, product_id),
      backend/database/schema.sql:166:  CONSTRAINT fk_cart_items_product FOREIGN KEY (product_id) REFERENCES products (product_id) ON DELETE RESTRICT,
      backend/database/schema.sql:196:  product_id BIGINT UNSIGNED NOT NULL,
      backend/database/schema.sql:200:  CONSTRAINT uq_order_items_product UNIQUE (order_id, product_id),
      backend/database/schema.sql:202:  CONSTRAINT fk_order_items_product FOREIGN KEY (product_id) REFERENCES products (product_id) ON DELETE RESTRICT,
      products.html:19:		<header class="site-header">
      products.html:38:							action="products.html"
      products.html:45:								aria-label="Search products"
      products.html:51:						<div class="header-actions ms-lg-auto">
      products.html:52:							<a class="header-action header-button" href="index.html#accountModal">
      products.html:56:							<a class="header-action cart-action" href="cart.html">
      products.html:67:		</header>
      products.html:109:							<div class="row g-4" id="product-grid">
      products.html:135:						<a href="products.html">Sản phẩm</a>
      products.html:168:		<script src="js/products.js"></script>
      backend/src/Repositories/ProductRepository.php:4:final class ProductRepository
      backend/src/Repositories/ProductRepository.php:9:     * Reads the normalized products, brands, categories, and product_images tables.
      backend/src/Repositories/ProductRepository.php:11:    public function featured(int $limit = 4): array
      backend/src/Repositories/ProductRepository.php:14:            'SELECT p.product_id AS id, p.product_slug AS slug, p.product_name AS name,
      backend/src/Repositories/ProductRepository.php:17:             FROM products p
      backend/src/Repositories/ProductRepository.php:20:             LEFT JOIN product_images image ON image.product_id = p.product_id AND image.display_order = 1
      backend/src/Repositories/ProductRepository.php:21:             WHERE p.product_status = :status AND p.is_featured = 1
      backend/src/Repositories/ProductRepository.php:34:        $where = ['p.product_status = :status'];
      backend/src/Repositories/ProductRepository.php:38:            $where[] = '(p.product_name LIKE :query OR b.brand_name LIKE :query OR c.category_name LIKE :query)';
      backend/src/Repositories/ProductRepository.php:47:            'SELECT p.product_id AS id, p.product_slug AS slug, p.product_name AS name,
      backend/src/Repositories/ProductRepository.php:50:             FROM products p
      backend/src/Repositories/ProductRepository.php:53:             LEFT JOIN product_images image ON image.product_id = p.product_id AND image.display_order = 1
      backend/api/products.php:6:    $repository = new ProductRepository(database());
      backend/api/products.php:9:    $featured = filter_var($_GET['featured'] ?? false, FILTER_VALIDATE_BOOL);
      backend/api/products.php:10:    $products = $featured
      backend/api/products.php:11:        ? $repository->featured((int) ($_GET['limit'] ?? 4))
      backend/api/products.php:14:    jsonResponse(['ok' => true, 'data' => $products]);
      js/product-detail.js:2: * Dynamic product detail page renderer
      js/product-detail.js:10:	const productType = params.get("type") === "mainboard" ? "mainboard" : "cpu";
      js/product-detail.js:11:	const sourcePath = `assets/products/${productType}/sources.csv`;
      js/product-detail.js:12:	const modelField = productType === "mainboard" ? "Tên sản phẩm" : "Model";
      js/product-detail.js:14:	const productList = await fetchAndParseCSV(sourcePath, productType === "cpu" ? undefined : []);
      js/product-detail.js:15:	if (!productList || productList.length === 0) {
      js/product-detail.js:16:		console.warn(`Không tải được dữ liệu ${productType} từ sources.csv`);
      js/product-detail.js:21:	let product = null;
      js/product-detail.js:23:		product = productList.find((p) => {
      js/product-detail.js:35:	if (!product) {
      js/product-detail.js:36:		product =
      js/product-detail.js:37:			productType === "cpu"
      js/product-detail.js:38:				? productList.find((p) => (p.Model || "").includes("12400F")) || productList[0]
      js/product-detail.js:39:				: productList[0];
      js/product-detail.js:42:	renderProductDetail(product, productType);
      js/product-detail.js:45:function renderProductDetail(product, productType) {
      js/product-detail.js:46:	const model = productType === "mainboard" ? product["Tên sản phẩm"] : product.Model;
      js/product-detail.js:47:	const fullName = `${productType === "mainboard" ? "Mainboard" : "CPU"} ${product.Hãng} ${model}`;
      js/product-detail.js:48:	const priceNum = Number((product["Giá TB (VNĐ)"] || "0").replace(/[^\d]/g, ""));
      js/product-detail.js:49:	const images = (product["Ảnh"] || "")
      js/product-detail.js:53:		.map((img) => `assets/products/${productType}/${img}`);
      js/product-detail.js:59:	const breadcrumbName = document.querySelector("#breadcrumb-product-name");
      js/product-detail.js:63:	const brandEl = document.querySelector("#product-brand");
      js/product-detail.js:65:		brandEl.textContent = product.Hãng;
      js/product-detail.js:69:	const nameEl = document.querySelector("#product-name");
      js/product-detail.js:72:	const modelEl = document.querySelector("#product-model");
      js/product-detail.js:75:			productType === "mainboard"
      js/product-detail.js:76:				? `Socket: ${product.Socket} · ${product["Chuẩn RAM (DDR4/DDR5)"]} · ${product["Form factor (ATX/mATX/ITX)"]}`
      js/product-detail.js:77:				: `Socket: ${product.Socket} · ${product["Số nhân/luồng"] || ""} · ${product["Xung nhịp"] || ""}`;
      js/product-detail.js:80:	const priceEl = document.querySelector("#product-price");
      js/product-detail.js:82:		priceEl.textContent = product["Giá TB (VNĐ)"] || "Liên hệ";
      js/product-detail.js:86:	const descEl = document.querySelector("#product-desc");
      js/product-detail.js:89:			product.iGPU && product.iGPU !== "Không có"
      js/product-detail.js:90:				? `Tích hợp nhân đồ họa ${product.iGPU}.`
      js/product-detail.js:94:			productType === "mainboard"
      js/product-detail.js:95:				? `Bo mạch chủ ${product.Hãng} ${model}, chuẩn Socket ${product.Socket}, hỗ trợ ${product["Chuẩn RAM (DDR4/DDR5)"]} và kích thước ${product["Form factor (ATX/mATX/ITX)"]}.`
      js/product-detail.js:96:				: `Bộ vi xử lý ${product.Hãng} ${model} chuẩn Socket ${product.Socket}, cấu hình ${product["Số nhân/luồng"] || "đa nhân"}, tốc độ tối đa ${product["Xung nhịp"] || ""}, công suất tiêu thụ cơ bản ${product.TDP || "65W"}. ${igpuText}`;
      js/product-detail.js:100:	const mainImg = document.querySelector("#main-product-img");
      js/product-detail.js:102:	const zoomContainer = mainImg?.closest(".product-image-zoom");
      js/product-detail.js:133:							class="product-thumb ${index === 0 ? "active" : ""}"
      js/product-detail.js:140:			thumbsContainer.querySelectorAll(".product-thumb").forEach((thumb) => {
      js/product-detail.js:143:						.querySelectorAll(".product-thumb")
      js/product-detail.js:161:			productType === "mainboard"
      js/product-detail.js:163:						{ label: "Hãng sản xuất", value: product.Hãng },
      js/product-detail.js:165:						{ label: "Chuẩn Socket", value: product.Socket },
      js/product-detail.js:166:						{ label: "Chuẩn RAM", value: product["Chuẩn RAM (DDR4/DDR5)"] },
      js/product-detail.js:167:						{ label: "Form factor", value: product["Form factor (ATX/mATX/ITX)"] },
      js/product-detail.js:172:						{ label: "Hãng sản xuất", value: product.Hãng },
      js/product-detail.js:174:						{ label: "Chuẩn Socket", value: product.Socket },
      js/product-detail.js:175:						{ label: "Số nhân / Số luồng", value: product["Số nhân/luồng"] || "---" },
      js/product-detail.js:176:						{ label: "Xung nhịp tối đa", value: product["Xung nhịp"] || "---" },
      js/product-detail.js:177:						{ label: "Điện năng tiêu thụ (TDP)", value: product.TDP || "---" },
      js/product-detail.js:178:						{ label: "Đồ họa tích hợp (iGPU)", value: product.iGPU || "Không có" },
      js/product-detail.js:199:			const qtyInput = document.querySelector("#product-qty");
      js/product-detail.js:203:				id: toSlug(`${productType}-${model}`) || Date.now().toString(),
      js/product-detail.js:205:				brand: product.Hãng || "TNC STORE",
      js/product-detail.js:207:				icon: productType === "mainboard" ? "bi bi-motherboard" : "bi bi-cpu",
      js/product-detail.js:208:				imageClass: "product-image",
      js/products.js:2: * Product catalog dynamic renderer with filter, search, and sort
      js/products.js:6:	const gridContainer = document.querySelector("#product-grid");
      js/products.js:7:	const countEl = document.querySelector("#product-count");
      js/products.js:16:		fetchAndParseCSV("assets/products/cpu/sources.csv"),
      js/products.js:17:		fetchAndParseCSV("assets/products/mainboard/sources.csv", []),
      js/products.js:20:	let products = cpuList.map((p) => {
      js/products.js:22:		const imgSrc = firstImg ? `assets/products/cpu/${firstImg}` : "assets/img/branding/tnc.png";
      js/products.js:35:			detailUrl: `product-detail.html?type=cpu&model=${encodeURIComponent(p.Model)}`,
      js/products.js:39:	products = products.concat(
      js/products.js:44:				? `assets/products/mainboard/${firstImg}`
      js/products.js:59:				detailUrl: `product-detail.html?type=mainboard&model=${encodeURIComponent(model)}`,
      js/products.js:64:	if (products.length === 0) return;
      js/products.js:68:		products = products.filter(
      js/products.js:94:						<article class="product-card d-flex w-100 flex-column">
      js/products.js:95:							<a href="${p.detailUrl}" class="product-image p-3 text-center bg-white d-block text-decoration-none">
      js/products.js:97:								${p.Ảnh ? '<span class="product-tag">Chính hãng</span>' : ""}
      js/products.js:99:							<p class="product-brand">${p.Hãng} · Socket ${p.Socket}</p>
      js/products.js:103:							<strong class="product-price">${p.priceStr}</strong>
      js/products.js:121:		let visibleProducts = selectedCategories.length
      js/products.js:122:			? products.filter((product) => selectedCategories.includes(product.category))
      js/products.js:123:			: [...products];
      js/products.js:126:			visibleProducts.sort((a, b) => a.priceNum - b.priceNum);
      js/products.js:128:			visibleProducts.sort((a, b) => b.priceNum - a.priceNum);
      js/products.js:131:		render(visibleProducts);
      js/products.js:135:		const count = products.filter((product) => product.category === input.value).length;

## Activity

    $ "C:\\Windows\\System32\\WindowsPowerShell\\v1.0\\powershell.exe" -Command 'Get-Content index.html -TotalCount 210; Get-Content products.html -TotalCount 120; Get-Content product-detail.html -TotalCount 120; Get-Content css/style.css -TotalCount 290; Get-Content js/home.js; Get-Content js/products.js -TotalCount 180'
    status: Completed · exit 0
      <!doctype html>
      <html>
      	<head>
      		<meta charset="utf-8" />
      		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
      		<title>TNC Store</title>
      		<meta name="description" content="" />
      		<meta name="viewport" content="width=device-width, initial-scale=1" />
      		<link rel="stylesheet" href="css/style.css" />
      		<link rel="icon" href="assets/favicon.png" />
      		<link
      			href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
      			rel="stylesheet"
      			integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
      			crossorigin="anonymous"
      		/>
      		<link
      			rel="stylesheet"
      			href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
      		/>
      		<link rel="preconnect" href="https://fonts.googleapis.com" />
      		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
      		<link
      			href="https://fonts.googleapis.com/css2?family=Chakra+Petch:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      			rel="stylesheet"
      		/>
      		<script
      			src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
      			integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
      			crossorigin="anonymous"
      		></script>
      	</head>
      	<body>
      		<div class="banner-top">
      			<a href=""></a>
      			<img src="assets/img/banner/banner-ad.png" alt="Banner top" />
      		</div>
      		<div class="header">
      			<div class="header-top">
      				<div class="container right">
      					<a href="tel:0868302123" class="item">
      						<i class="bi bi-telephone"></i>
      						<span>0868 302 123</span>
      					</a>
      					<a href="mailto:cskh@tncstore.vn" class="item">
      						<i class="bi bi-envelope"></i>
      						<span>cskh@tncstore.vn</span>
      					</a>
      				</div>
      			</div>
      			<div class="header-mid container-fluid">
      				<nav class="navbar navbar-expand-lg py-3" aria-label="Main navigation">
      					<a class="navbar-brand logo" href="index.html" aria-label="TNC Store home">
      						<img src="assets/img/branding/tnc.png" alt="TNC Store" />
      					</a>
      					<button
      						class="navbar-toggler"
      						type="button"
      						data-bs-toggle="collapse"
      						data-bs-target="#storeNavigation"
      						aria-controls="storeNavigation"
      						aria-expanded="false"
      						aria-label="Toggle navigation"
      					>
      						<span class="navbar-toggler-icon"></span>
      					</button>
      					<div class="collapse navbar-collapse" id="storeNavigation">
      						<div class="dropdown category-dropdown my-3 my-lg-0">
      							<button
      								class="category-toggle dropdown-toggle"
      								type="button"
      								data-bs-toggle="dropdown"
      								aria-expanded="false"
      							>
      								<i class="bi bi-grid-3x3-gap me-2"></i>
      								Danh má»¥c
      							</button>
      							<ul class="dropdown-menu">
      								<li>
      									<a class="dropdown-item" href="buildpc.html">Build PC</a>
      								</li>
      								<li>
      									<a class="dropdown-item" href="products.html?category=pc">
      										PC & Linh kiá»‡n
      									</a>
      								</li>
      								<li>
      									<a class="dropdown-item" href="products.html?category=keyboard">
      										BÃ n phÃ­m
      									</a>
      								</li>
      								<li>
      									<a class="dropdown-item" href="products.html?category=monitor">
      										MÃ n hÃ¬nh
      									</a>
      								</li>
      								<li>
      									<a
      										class="dropdown-item"
      										href="products.html?category=gaming-gear"
      									>
      										Gaming gear
      									</a>
      								</li>
      							</ul>
      						</div>
      						<form
      							class="search-form mx-lg-4 my-3 my-lg-0"
      							role="search"
      							action="products.html"
      							method="get"
      						>
      							<label class="visually-hidden" for="site-search">Search products</label>
      							<input
      								id="site-search"
      								class="form-control"
      								type="search"
      								name="q"
      								placeholder="Báº¡n Ä‘ang tÃ¬m gÃ¬?"
      								autocomplete="off"
      							/>
      							<button class="search-button" type="submit" aria-label="Search">
      								<i class="bi bi-search" aria-hidden="true"></i>
      							</button>
      						</form>
      						<div class="header-actions ms-lg-auto">
      							<a
      								class="header-action header-button"
      								href="#accountModal"
      								data-bs-toggle="modal"
      								data-bs-target="#accountModal"
      							>
      								<i class="bi bi-person-circle" aria-hidden="true"></i>
      								<span>TÃ i khoáº£n</span>
      							</a>
      							<a class="header-action cart-action" href="cart.html">
      								<span class="cart-icon">
      									<i class="bi bi-cart3" aria-hidden="true"></i>
      									<b>0</b>
      								</span>
      								<span>Giá» hÃ ng</span>
      							</a>
      						</div>
      					</div>
      				</nav>
      			</div>
      			<div class="header-bottom">
      				<div class="container">
      					<div class="header-menu">
      						<div>
      							<span>Danh má»¥c sáº£n pháº©m</span>
      						</div>
      					</div>
      					<div class="list">
      						<a href="buildpc.php" class="item">
      							<span>Build PC</span>
      						</a>
      					</div>
      				</div>
      			</div>
      		</div>
      		<main>
      			<section class="promo-carousel-section">
      				<div id="storePromoCarousel" class="carousel slide" data-bs-ride="carousel">
      						<div class="carousel-inner">
      							<div class="carousel-indicators">
      								<button
      									type="button"
      									data-bs-target="#storePromoCarousel"
      									data-bs-slide-to="0"
      									class="active"
      									aria-current="true"
      									aria-label="Back To School"
      								></button>
      								<button
      									type="button"
      									data-bs-target="#storePromoCarousel"
      									data-bs-slide-to="1"
      									aria-label="Build PC Asus Rinh quÃ  háº¿t náº¥c"
      								></button>
      								<button
      									type="button"
      									data-bs-target="#storePromoCarousel"
      									data-bs-slide-to="2"
      									aria-label="Build PC Gigabyte Intel"
      								></button>
      								<button
      									type="button"
      									data-bs-target="#storePromoCarousel"
      									data-bs-slide-to="3"
      									aria-label="Äá»“ng hÃ nh trá»Ÿ vá» cÄƒn cá»© Asus"
      								></button>
      								<button
      									type="button"
      									data-bs-target="#storePromoCarousel"
      									data-bs-slide-to="4"
      									aria-label="PC AI Gigabyte Web"
      								></button>
      								<button
      									type="button"
      									data-bs-target="#storePromoCarousel"
      									data-bs-slide-to="5"
      									aria-label="Razer len deal gear len doi"
      								></button>
      								<button
      									type="button"
      									data-bs-target="#storePromoCarousel"
      									data-bs-slide-to="6"
      									aria-label="Asus Miku Gear"
      								></button>
      <!doctype html>
      <html lang="vi">
      	<head>
      		<meta charset="utf-8" />
      		<meta name="viewport" content="width=device-width, initial-scale=1" />
      		<title>Sáº£n pháº©m | TNC Store</title>
      		<link rel="stylesheet" href="css/style.css" />
      		<link rel="icon" href="assets/favicon.png" />
      		<link
      			href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
      			rel="stylesheet"
      		/>
      		<link
      			rel="stylesheet"
      			href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
      		/>
      	</head>
      	<body>
      		<header class="site-header">
      			<div class="container">
      				<nav class="navbar navbar-expand-lg py-3" aria-label="Main navigation">
      					<a class="navbar-brand logo" href="index.html">
      						<img src="assets/img/branding/tnc.png" alt="TNC Store" />
      					</a>
      					<button
      						class="navbar-toggler"
      						type="button"
      						data-bs-toggle="collapse"
      						data-bs-target="#storeNavigation"
      						aria-label="Toggle navigation"
      					>
      						<span class="navbar-toggler-icon"></span>
      					</button>
      					<div class="collapse navbar-collapse" id="storeNavigation">
      						<form
      							class="search-form mx-lg-4 my-3 my-lg-0"
      							role="search"
      							action="products.html"
      						>
      							<input
      								class="form-control"
      								type="search"
      								name="q"
      								placeholder="Báº¡n Ä‘ang tÃ¬m gÃ¬?"
      								aria-label="Search products"
      							/>
      							<button class="search-button" type="submit" aria-label="Search">
      								<i class="bi bi-search"></i>
      							</button>
      						</form>
      						<div class="header-actions ms-lg-auto">
      							<a class="header-action header-button" href="index.html#accountModal">
      								<i class="bi bi-person-circle"></i>
      								<span>TÃ i khoáº£n</span>
      							</a>
      							<a class="header-action cart-action" href="cart.html">
      								<span class="cart-icon">
      									<i class="bi bi-cart3"></i>
      									<b>0</b>
      								</span>
      								<span>Giá» hÃ ng</span>
      							</a>
      						</div>
      					</div>
      				</nav>
      			</div>
      		</header>
      		<main>
      			<section class="section-space">
      				<div class="container">
      					<div class="row g-4">
      						<aside class="col-lg-3">
      							<div class="filter-panel">
      								<h2>Bá»™ lá»c</h2>
      								<label class="filter-check">
      									<input
      										type="checkbox"
      										value="components"
      										data-category-filter
      									/>
      									PC & Linh kiá»‡n
      									<span data-category-count="components">0</span>
      								</label>
      								<label class="filter-check">
      									<input type="checkbox" value="keyboard" data-category-filter />
      									BÃ n phÃ­m
      									<span data-category-count="keyboard">0</span>
      								</label>
      								<label class="filter-check">
      									<input type="checkbox" value="monitor" data-category-filter />
      									MÃ n hÃ¬nh
      									<span data-category-count="monitor">0</span>
      								</label>
      								<label class="filter-check">
      									<input type="checkbox" value="gaming" data-category-filter />
      									Gaming gear
      									<span data-category-count="gaming">0</span>
      								</label>
      								<hr />
      								<label for="sort-price" class="filter-label">Sáº¯p xáº¿p theo</label>
      								<select id="sort-price" class="form-select">
      									<option>Ná»•i báº­t nháº¥t</option>
      									<option>GiÃ¡ tháº¥p Ä‘áº¿n cao</option>
      									<option>GiÃ¡ cao Ä‘áº¿n tháº¥p</option>
      								</select>
      							</div>
      						</aside>
      						<div class="col-lg-9">
      							<div class="row g-4" id="product-grid">
      								<!-- Rendered dynamically from CSV -->
      								<div class="col-12 py-5 text-center text-muted">
      									<div
      										class="spinner-border spinner-border-sm me-2"
      										role="status"
      									></div>
      									Äang náº¡p danh sÃ¡ch sáº£n pháº©m...
      								</div>
      							</div>
      						</div>
      					</div>
      <!doctype html>
      <html lang="vi">
      	<head>
      		<meta charset="utf-8" />
      		<meta name="viewport" content="width=device-width, initial-scale=1" />
      		<title id="page-title">Chi tiáº¿t sáº£n pháº©m | TNC Store</title>
      		<link rel="stylesheet" href="css/style.css" />
      		<link rel="icon" href="assets/favicon.png" />
      		<link
      			href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
      			rel="stylesheet"
      		/>
      		<link
      			rel="stylesheet"
      			href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
      		/>
      	</head>
      	<body>
      		<header class="site-header">
      			<div class="container">
      				<nav class="navbar navbar-expand-lg py-3" aria-label="Main navigation">
      					<a class="navbar-brand logo" href="index.html" aria-label="TNC Store home">
      						<img src="assets/img/branding/tnc.png" alt="TNC Store" />
      					</a>
      					<button
      						class="navbar-toggler"
      						type="button"
      						data-bs-toggle="collapse"
      						data-bs-target="#storeNavigation"
      						aria-controls="storeNavigation"
      						aria-expanded="false"
      						aria-label="Toggle navigation"
      					>
      						<span class="navbar-toggler-icon"></span>
      					</button>
      					<div class="collapse navbar-collapse" id="storeNavigation">
      						<div class="dropdown category-dropdown my-3 my-lg-0">
      							<button
      								class="category-toggle dropdown-toggle"
      								type="button"
      								data-bs-toggle="dropdown"
      								aria-expanded="false"
      							>
      								<i class="bi bi-grid-3x3-gap me-2"></i>
      								Danh má»¥c
      							</button>
      							<ul class="dropdown-menu">
      								<li>
      									<a class="dropdown-item" href="buildpc.html">Build PC</a>
      								</li>
      								<li>
      									<a class="dropdown-item" href="products.html?category=pc">
      										PC & Linh kiá»‡n
      									</a>
      								</li>
      								<li>
      									<a class="dropdown-item" href="products.html?category=keyboard">
      										BÃ n phÃ­m
      									</a>
      								</li>
      								<li>
      									<a class="dropdown-item" href="products.html?category=monitor">
      										MÃ n hÃ¬nh
      									</a>
      								</li>
      								<li>
      									<a
      										class="dropdown-item"
      										href="products.html?category=gaming-gear"
      									>
      										Gaming gear
      									</a>
      								</li>
      							</ul>
      						</div>
      						<form
      							class="search-form mx-lg-4 my-3 my-lg-0"
      							role="search"
      							action="products.html"
      							method="get"
      						>
      							<label class="visually-hidden" for="site-search">Search products</label>
      							<input
      								id="site-search"
      								class="form-control"
      								type="search"
      								name="q"
      								placeholder="Báº¡n Ä‘ang tÃ¬m gÃ¬?"
      								autocomplete="off"
      							/>
      							<button class="search-button" type="submit" aria-label="Search">
      								<i class="bi bi-search" aria-hidden="true"></i>
      							</button>
      						</form>
      						<div class="header-actions ms-lg-auto">
      							<a class="header-action header-button" href="index.html#accountModal">
      								<i class="bi bi-person-circle" aria-hidden="true"></i>
      								<span>TÃ i khoáº£n</span>
      							</a>
      							<a class="header-action cart-action" href="cart.html">
      								<span class="cart-icon">
      									<i class="bi bi-cart3" aria-hidden="true"></i>
      									<b>0</b>
      								</span>
      								<span>Giá» hÃ ng</span>
      							</a>
      						</div>
      					</div>
      				</nav>
      			</div>
      		</header>
      
      		<main>
      			<div class="container">
      				<!-- Breadcrumb -->
      				<nav aria-label="breadcrumb" class="mb-4">
      					<ol class="breadcrumb mb-0">
      						<li class="breadcrumb-item"><a href="index.html">Trang chá»§</a></li>
      						<li class="breadcrumb-item">
      							<a href="products.html">Sáº£n pháº©m</a>
      :root {
      	--header-ink: #17212b;
      	--header-accent: #29324e;
      	--header-border: #e8ebee;
      	--color-global: #3278f6;
      	--color-red: #fb4e4e;
      }
      * {
      	font-family: "Chakra Petch" !important;
      	box-sizing: border-box;
      }
      
      body {
      	color: var(--header-ink);
      	background: #fff;
      }
      
      main {
      	padding: 5% 0 5% 0;
      }
      
      banner-top {
      	width: 100%;
      	margin: 0 auto;
      	background: #ff6300;
      	position: fixed;
      	margin: 0 auto;
      }
      banner-top img {
      	width:1600;
      	height: 100%;
      	display: block;
      }
      
      h1,
      h2,
      h3,
      p {
      	margin-top: 0;
      }
      h1,
      h2,
      h3 {
      	letter-spacing: -0.03em;
      }
      body .btn-primary {
      	--bs-btn-bg: var(--header-accent);
      	--bs-btn-border-color: var(--header-accent);
      	--bs-btn-hover-bg: #1d2640;
      	--bs-btn-hover-border-color: #1d2640;
      	--bs-btn-active-bg: #1d2640;
      	--bs-btn-active-border-color: #1d2640;
      	--bs-btn-disabled-bg: var(--header-accent);
      	--bs-btn-disabled-border-color: var(--header-accent);
      }
      body .btn-outline-primary {
      	--bs-btn-color: var(--header-accent);
      	--bs-btn-border-color: var(--header-accent);
      	--bs-btn-hover-bg: var(--header-accent);
      	--bs-btn-hover-border-color: var(--header-accent);
      	--bs-btn-hover-color: #fff;
      	--bs-btn-active-bg: var(--header-accent);
      	--bs-btn-active-border-color: var(--header-accent);
      	--bs-btn-active-color: #fff;
      }
      body .btn-link {
      	--bs-btn-color: var(--header-accent);
      	color: var(--header-accent);
      }
      body .btn-link:hover {
      	color: #1d2640;
      }
      /* Override Bootstrap's default blue focus ring */
      .btn:focus-visible,
      .btn:focus,
      .btn:active:focus,
      .btn.active:focus,
      .btn.show {
      	box-shadow: 0 0 0 0.25rem rgba(41, 51, 79, 0.25);
      	outline: none;
      }
      
      .banner-top {
      	background: #ff6300;
      	width: 100%;
      	margin: 0 auto;
      	position: relative;
      }
      /* Header */
      .header {
      	width: 100%;
      	background: #29324e;
      }
      .header .header-fixed {
      	position: fixed;
      	z-index: 999;
      	top: 0;
      }
      .header .header-top {
      	background-color: var(--color-global);
      	padding: 10px 0;
      	text-align: right;
      }
      .header .header-top .item {
      	border-right: 2px solid #2860c5;
      	padding: 0 15px;
      }
      .header .header-top .item:last-child {
      	border: none;
      	padding: 0 15px;
      }
      .header .header-top .item i {
      	margin-right: 5px;
      	color: #FFFFFF;
      }
      .header .header-top .item span {
      	color: #FFFFFF;
      	font-weight: 600;
      }
      .header .header-mid {
      	box-shadow: 0 2px 10px rgba(23, 33, 43, 0.05);
      	padding: 0 24px;
      }
      .header .container {
      	max-width: 1200px;
      }
      .header .header-bottom {
      
      }
      .logo {
      	display: inline-flex;
      	align-items: center;
      	gap: 9px;
      	color: var(--header-ink);
      	font-weight: 800;
      	letter-spacing: 0.04em;
      	line-height: 1;
      	text-decoration: none;
      	white-space: nowrap;
      }
      
      .logo:hover {
      	color: var(--header-accent);
      }
      
      .logo img {
      	max-width: 10rem;
      	height: auto;
      	object-fit: contain;
      }
      
      .search-form {
      	position: relative;
      	flex: 1 1 460px;
      }
      
      .category-dropdown {
      	flex: 0 0 auto;
      }
      /* Home carousel */
      
      .category-toggle {
      	min-height: 44px;
      	padding: 0.65rem 1rem;
      	border: 1px solid #d9dee3;
      	border-radius: 6px;
      	background: #fff;
      	color: var(--header-ink);
      	font-size: 0.86rem;
      	font-weight: 700;
      	white-space: nowrap;
      }
      
      .category-toggle:hover,
      .category-toggle:focus,
      .category-toggle.show {
      	border-color: var(--header-accent);
      	background: #f5f7fa;
      	color: var(--header-accent);
      }
      
      .category-dropdown .dropdown-menu {
      	min-width: 220px;
      	margin-top: 0.5rem;
      	padding: 0.5rem;
      	border: 1px solid var(--header-border);
      	border-radius: 4px;
      	box-shadow: 0 12px 28px rgba(23, 33, 43, 0.12);
      }
      
      .category-dropdown .dropdown-item {
      	padding: 0.7rem 0.8rem;
      	border-radius: 3px;
      	color: var(--header-ink);
      	font-size: 0.84rem;
      }
      
      .category-dropdown .dropdown-item:hover,
      .category-dropdown .dropdown-item:focus {
      	background: #eef1f6;
      	color: var(--header-accent);
      }
      
      .search-form .form-control {
      	min-height: 44px;
      	padding: 0.65rem 3.2rem 0.65rem 1rem;
      	border: 1px solid #d9dee3;
      	border-radius: 6px;
      	background: #f8f9fa;
      }
      
      .search-form .form-control:focus {
      	border-color: var(--header-accent);
      	box-shadow: 0 0 0 2px rgba(41, 51, 79, 0.14);
      }
      
      .search-button {
      	position: absolute;
      	top: 4px;
      	right: 4px;
      	width: 36px;
      	height: 36px;
      	border: 0;
      	border-radius: 5px;
      	background: var(--header-accent);
      	color: #fff;
      	font-size: 1rem;
      }
      
      .header-actions {
      	display: flex;
      	align-items: center;
      	gap: clamp(1rem, 2.5vw, 2rem);
      }
      
      .header-action {
      	display: inline-flex;
      	align-items: center;
      	gap: 9px;
      	color: #FFFFFF;
      	font-size: 0.85rem;
      	font-weight: 600;
      	line-height: 1.2;
      	text-decoration: none;
      	white-space: nowrap;
      }
      
      .header-button {
      	border: 0;
      	background: transparent;
      	padding: 0;
      }
      
      .header-action > i,
      .cart-icon > i {
      	color: #FFFFFF;
      	font-size: 1.65rem;
      }
      
      .header-action small {
      	display: block;
      	color: #68737d;
      	font-size: 0.7rem;
      	font-weight: 400;
      	margin-bottom: 2px;
      }
      
      /* Home carousel */
      .promo-slide {
      	color: #fff;
      }
      .promo-slide > .container {
      	position: relative;
      	display: flex;
      	align-items: center;
      }
      
      .promo-carousel-section .carousel-inner {
      	position: relative;
      }
      .promo-carousel-section > .container {
      	padding: 0 12px;
      }
      .promo-carousel-section .carousel-indicators {
      	z-index: 2;
      	justify-content: center;
      	width: min(100% - 2rem, 1140px);
      	margin-right: auto;
      	margin-bottom: 0;
      	margin-left: auto;
      /**
       * Home page dynamic renderer
       */
      
      document.addEventListener("DOMContentLoaded", async () => {
      	const container = document.querySelector("#featured-products-list");
      	if (!container) return;
      	const escapeHtml = (value) =>
      		String(value ?? "").replace(/[&<>'"]/g, (character) =>
      			({ "&": "&amp;", "<": "&lt;", ">": "&gt;", "'": "&#39;", '"': "&quot;" })[character],
      		);
      
      	// MySQL-backed products take priority once the database is configured.
      	// The CSV renderer below remains a local-development fallback until then.
      	try {
      		const apiBase = window.TNC_API_BASE || "backend/api";
      		const response = await fetch(`${apiBase}/products.php?featured=1&limit=4`);
      		const payload = await response.json();
      		if (response.ok && payload.ok && Array.isArray(payload.data) && payload.data.length) {
      			container.innerHTML = payload.data
      				.map((product) => {
      					const name = escapeHtml(product.name);
      					const brand = escapeHtml(product.brand || "TNC Store");
      					const socket = escapeHtml(product.socket || "LiÃªn há»‡");
      					const image = escapeHtml(product.image || "assets/img/branding/tnc.png");
      					const url = `product-detail.php?slug=${encodeURIComponent(product.slug)}`;
      					const price = new Intl.NumberFormat("vi-VN").format(Number(product.price) || 0);
      					return `<div class="col-sm-6 col-lg-3 d-flex"><article class="product-card d-flex w-100 flex-column"><a href="${url}" class="product-image p-3 text-center bg-white d-block text-decoration-none"><img src="${image}" alt="${name}" class="img-fluid" style="height: 160px; object-fit: contain;"><span class="product-tag">BÃ¡n cháº¡y</span></a><p class="product-brand">${brand} Â· ${socket}</p><h3><a href="${url}" class="text-decoration-none text-dark">${name}</a></h3><strong class="product-price">${price} Ä‘</strong><button class="btn btn-outline-primary w-100 mt-auto" type="button"><i class="bi bi-cart-plus me-2"></i>ThÃªm vÃ o giá»</button></article></div>`;
      				})
      				.join("");
      			return;
      		}
      	} catch {
      		// The API is intentionally optional until MySQL and its schema are available.
      	}
      
      	const cpuList = await fetchAndParseCSV("assets/products/cpu/sources.csv");
      	if (!cpuList || cpuList.length === 0) return;
      
      	// Láº¥y 4 sáº£n pháº©m CPU tiÃªu biá»ƒu cÃ³ áº£nh
      	const featured = cpuList.filter((p) => p.áº¢nh && p.áº¢nh.trim().length > 0).slice(0, 4);
      
      	container.innerHTML = featured
      		.map((p) => {
      			const firstImg = p.áº¢nh.split("|")[0].trim();
      			const imgSrc = `assets/products/cpu/${firstImg}`;
      			const detailUrl = `product-detail.html?model=${encodeURIComponent(p.Model)}`;
      			const fullName = `CPU ${p.HÃ£ng} ${p.Model}`;
      			const price = p["GiÃ¡ TB (VNÄ)"] || "LiÃªn há»‡";
      
      			return `
      				<div class="col-sm-6 col-lg-3 d-flex">
      					<article class="product-card d-flex w-100 flex-column">
      						<a href="${detailUrl}" class="product-image p-3 text-center bg-white d-block text-decoration-none">
      							<img src="${imgSrc}" alt="${fullName}" class="img-fluid" style="height: 160px; object-fit: contain;">
      							<span class="product-tag">BÃ¡n cháº¡y</span>
      						</a>
      						<p class="product-brand">${p.HÃ£ng} Â· Socket ${p.Socket}</p>
      						<h3>
      							<a href="${detailUrl}" class="text-decoration-none text-dark">${fullName}</a>
      						</h3>
      						<strong class="product-price">${price}</strong>
      						<button class="btn btn-outline-primary w-100 mt-auto" type="button">
      							<i class="bi bi-cart-plus me-2"></i>
      							ThÃªm vÃ o giá»
      						</button>
      					</article>
      				</div>
      			`;
      		})
      		.join("");
      });
      /**
       * Product catalog dynamic renderer with filter, search, and sort
       */
      
      document.addEventListener("DOMContentLoaded", async () => {
      	const gridContainer = document.querySelector("#product-grid");
      	const countEl = document.querySelector("#product-count");
      	const sortSelect = document.querySelector("#sort-price");
      	const categoryInputs = [...document.querySelectorAll("[data-category-filter]")];
      	const searchParams = new URLSearchParams(window.location.search);
      	const querySearch = (searchParams.get("q") || "").trim().toLowerCase();
      
      	if (!gridContainer) return;
      
      	const [cpuList, mainboardList] = await Promise.all([
      		fetchAndParseCSV("assets/products/cpu/sources.csv"),
      		fetchAndParseCSV("assets/products/mainboard/sources.csv", []),
      	]);
      
      	let products = cpuList.map((p) => {
      		const firstImg = (p.áº¢nh || "").split("|")[0].trim();
      		const imgSrc = firstImg ? `assets/products/cpu/${firstImg}` : "assets/img/branding/tnc.png";
      		const fullName = `CPU ${p.HÃ£ng} ${p.Model}`;
      		const priceStr = p["GiÃ¡ TB (VNÄ)"] || "0 Ä‘";
      		const priceNum = Number(priceStr.replace(/[^\d]/g, "")) || 0;
      
      		return {
      			...p,
      			type: "cpu",
      			category: "components",
      			fullName,
      			imgSrc,
      			priceStr,
      			priceNum,
      			detailUrl: `product-detail.html?type=cpu&model=${encodeURIComponent(p.Model)}`,
      		};
      	});
      
      	products = products.concat(
      		mainboardList.map((p) => {
      			const model = p["TÃªn sáº£n pháº©m"];
      			const firstImg = (p.áº¢nh || "").split("|")[0].trim();
      			const imgSrc = firstImg
      				? `assets/products/mainboard/${firstImg}`
      				: "assets/img/branding/tnc.png";
      			const fullName = `Mainboard ${p.HÃ£ng} ${model}`;
      			const priceStr = p["GiÃ¡ TB (VNÄ)"] || "0 Ä‘";
      			const priceNum = Number(priceStr.replace(/[^\d]/g, "")) || 0;
      
      			return {
      				...p,
      				type: "mainboard",
      				category: "components",
      				model,
      				fullName,
      				imgSrc,
      				priceStr,
      				priceNum,
      				detailUrl: `product-detail.html?type=mainboard&model=${encodeURIComponent(model)}`,
      			};
      		}),
      	);
      
      	if (products.length === 0) return;
      
      	// Lá»c theo tá»« khÃ³a tÃ¬m kiáº¿m náº¿u cÃ³
      	if (querySearch) {
      		products = products.filter(
      			(p) =>
      				p.fullName.toLowerCase().includes(querySearch) ||
      				(p.Socket && p.Socket.toLowerCase().includes(querySearch)) ||
      				(p.HÃ£ng && p.HÃ£ng.toLowerCase().includes(querySearch)),
      		);
      	}
      
      	function render(list) {
      		if (countEl) countEl.textContent = `${list.length} sáº£n pháº©m`;
      
      		if (list.length === 0) {
      			gridContainer.innerHTML = `
      				<div class="col-12 py-5 text-center text-muted">
      					<i class="bi bi-search fs-1 d-block mb-3"></i>
      					<h5>KhÃ´ng tÃ¬m tháº¥y sáº£n pháº©m phÃ¹ há»£p</h5>
      					<p>Thá»­ tÃ¬m kiáº¿m vá»›i tá»« khÃ³a khÃ¡c hoáº·c bá» cÃ¡c bá»™ lá»c.</p>
      				</div>
      			`;
      			return;
      		}
      
      		gridContainer.innerHTML = list
      			.map(
      				(p) => `
      					<div class="col-sm-6 col-xl-4 d-flex">
      						<article class="product-card d-flex w-100 flex-column">
      							<a href="${p.detailUrl}" class="product-image p-3 text-center bg-white d-block text-decoration-none">
      								<img src="${p.imgSrc}" alt="${p.fullName}" class="img-fluid" style="height: 150px; object-fit: contain;">
      								${p.áº¢nh ? '<span class="product-tag">ChÃ­nh hÃ£ng</span>' : ""}
      							</a>
      							<p class="product-brand">${p.HÃ£ng} Â· Socket ${p.Socket}</p>
      							<h3>
      								<a href="${p.detailUrl}" class="text-decoration-none text-dark">${p.fullName}</a>
      							</h3>
      							<strong class="product-price">${p.priceStr}</strong>
      							<div class="mt-auto pt-2">
      								<button class="btn btn-outline-primary w-100" type="button">
      									<i class="bi bi-cart-plus me-2"></i>
      									ThÃªm vÃ o giá»
      								</button>
      							</div>
      						</article>
      					</div>
      				`,
      			)
      			.join("");
      	}
      
      	function applyFiltersAndSort() {
      		const selectedCategories = categoryInputs
      			.filter((input) => input.checked)
      			.map((input) => input.value);
      		let visibleProducts = selectedCategories.length
      			? products.filter((product) => selectedCategories.includes(product.category))
      			: [...products];
      
      		if (sortSelect?.value.includes("tháº¥p Ä‘áº¿n cao")) {
      			visibleProducts.sort((a, b) => a.priceNum - b.priceNum);
      		} else if (sortSelect?.value.includes("cao Ä‘áº¿n tháº¥p")) {
      			visibleProducts.sort((a, b) => b.priceNum - a.priceNum);
      		}
      
      		render(visibleProducts);
      	}
      
      	categoryInputs.forEach((input) => {
      		const count = products.filter((product) => product.category === input.value).length;
      		const countEl = document.querySelector(`[data-category-count="${input.value}"]`);
      		if (countEl) countEl.textContent = count;
      		input.addEventListener("change", applyFiltersAndSort);
      	});
      
      	sortSelect?.addEventListener("change", applyFiltersAndSort);
      	applyFiltersAndSort();
      });

## Activity

    $ "C:\\Windows\\System32\\WindowsPowerShell\\v1.0\\powershell.exe" -Command "rg -l \"<header|<div class=\\\"header\\\"|site-header|header-mid\" -g \"*.html\"; rg -n \"product-card|featured-products-list|CPU Intel|3\\.200\\.000|assets/products\" -g \"*.html\"; Get-Content js/header.js; Get-Content backend/README.md; Get-Content backend/config/database.php; Get-Content backend/src/Repositories/ProductRepository.php; git status --short"
    status: Completed · exit 0
      window.addEventListener("scroll", () => {
          if (window.scrollY > 785) {
              document.querySelector(".header").classList.add("header-fixed");
          } else {
              document.querySelector(".header").classList.remove("header-fixed");
          }
      });
      # PHP + MySQL backend skeleton
      
      The storefront now has PHP entry points (`index.php`, `products.php`, `news.php`, and the other page counterparts). They render the existing Bootstrap UI through `backend/views/legacy-page.php`, so the visual site stays intact while the data layer is migrated.
      
      ## Local setup
      
      1. Install PHP 8.1+ with the `pdo_mysql` extension and MySQL.
      2. Copy `backend/config/.env.example` to `backend/config/.env`.
      3. Fill in the `DB_*` values after creating the database and schema.
      4. Serve the project root with Apache/XAMPP, or run `php -S localhost:8000` from the project root.
      5. Open `http://localhost:8000/` (Apache loads `index.php` first via `.htaccess`).
      
      No SQL is included or executed by this repository. Until the database is configured, the browser preserves the CSV-based product fallback and API calls respond with a safe `503` JSON message.
      
      ## Schema contract for your SQL
      
      The repositories deliberately document only the columns they use:
      
      - `products` belongs to a `brand` and a `category`; images and specifications use child tables.
      - `news_posts` belongs to a `news_category` and can reference a `users` author.
      - `users` belongs to a `role`; shipping addresses are stored in `addresses`.
      
      The complete MySQL 8 schema, including columns and keys, is [database/schema.sql](database/schema.sql).
      
      ## Normalization and integrity review
      
      The schema is designed to be in BCNF for its stored facts: brands, categories, roles, statuses, product images, inventory, cart lines, and order lines each have a key that determines their non-key attributes. Product specifications and inventory use composite keys because their values are facts about a specific product/specification or warehouse/product pair.
      
      Derived checkout totals are intentionally not stored in `orders`; calculate them from `order_items`, `shipping_amount`, and `discount_amount`. This avoids the non-key dependency that a stored total would introduce. The schema also enforces positive quantities, non-negative prices/stock, one default address per user, unique public identifiers/slugs, valid cart ownership, and foreign-key referential integrity.
      
      Two multi-row business rules should be enforced inside the transaction that creates or updates an order: inventory availability and reconciliation of the calculated order total. Category-tree cycle detection is likewise an application/service rule beyond the direct self-parent check in MySQL.
      
      `status` values expected by the provided queries are `active` for products and `published` for news posts. User passwords are stored only through `password_hash()`; never insert plaintext passwords.
      
      ## Available endpoints
      
      - `GET backend/api/products.php?featured=1&limit=4`
      - `GET backend/api/products.php?q=...&category=...&limit=24`
      - `GET backend/api/news.php?limit=8`
      - `GET backend/api/news.php?slug=...`
      - `POST backend/auth/register.php` with `name`, `email`, `password`
      - `POST backend/auth/login.php` with `email`, `password`
      
      Both auth endpoints accept regular form data or JSON and return JSON. They create and use the `tnc_store_session` PHP session. Cart, order, contact, and admin database operations are intentionally left for the schema/business rules you add next.
      
      ## Project layout
      
      ```text
      backend/
        bootstrap.php                 # environment, session, shared dependencies
        config/database.php           # PDO connection factory
        config/.env.example           # never commit the real .env
        api/                          # read endpoints
        auth/                         # registration and login actions
        src/Repositories/             # prepared-statement data access
        views/legacy-page.php         # temporary PHP migration bridge
      ```
      <?php
      declare(strict_types=1);
      
      function database(): PDO
      {
          static $connection = null;
      
          if ($connection instanceof PDO) {
              return $connection;
          }
      
          $host = getenv('DB_HOST') ?: '127.0.0.1';
          $port = getenv('DB_PORT') ?: '3306';
          $name = getenv('DB_DATABASE') ?: '';
          $user = getenv('DB_USERNAME') ?: '';
          $password = getenv('DB_PASSWORD') ?: '';
      
          if ($name === '' || $user === '') {
              throw new RuntimeException('Database is not configured. Copy backend/config/.env.example to .env and fill in the connection values.');
          }
      
          $connection = new PDO(
              "mysql:host={$host};port={$port};dbname={$name};charset=utf8mb4",
              $user,
              $password,
              [
                  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                  PDO::ATTR_EMULATE_PREPARES => false,
              ],
          );
      
          return $connection;
      }
      <?php
      declare(strict_types=1);
      
      final class ProductRepository
      {
          public function __construct(private readonly PDO $db) {}
      
          /**
           * Reads the normalized products, brands, categories, and product_images tables.
           */
          public function featured(int $limit = 4): array
          {
              $statement = $this->db->prepare(
                  'SELECT p.product_id AS id, p.product_slug AS slug, p.product_name AS name,
                          b.brand_name AS brand, c.category_slug AS category, p.unit_price AS price,
                          image.image_path AS image, p.socket
                   FROM products p
                   LEFT JOIN brands b ON b.brand_id = p.brand_id
                   INNER JOIN categories c ON c.category_id = p.category_id
                   LEFT JOIN product_images image ON image.product_id = p.product_id AND image.display_order = 1
                   WHERE p.product_status = :status AND p.is_featured = 1
                   ORDER BY p.created_at DESC
                   LIMIT :limit',
              );
              $statement->bindValue(':status', 'active');
              $statement->bindValue(':limit', max(1, min($limit, 24)), PDO::PARAM_INT);
              $statement->execute();
      
              return $statement->fetchAll();
          }
      
          public function search(string $query = '', ?string $category = null, int $limit = 24): array
          {
              $where = ['p.product_status = :status'];
              $params = [':status' => 'active'];
      
              if ($query !== '') {
                  $where[] = '(p.product_name LIKE :query OR b.brand_name LIKE :query OR c.category_name LIKE :query)';
                  $params[':query'] = '%' . $query . '%';
              }
              if ($category !== null && $category !== '') {
                  $where[] = 'c.category_slug = :category';
                  $params[':category'] = $category;
              }
      
              $statement = $this->db->prepare(
                  'SELECT p.product_id AS id, p.product_slug AS slug, p.product_name AS name,
                          b.brand_name AS brand, c.category_slug AS category, p.unit_price AS price,
                          image.image_path AS image, p.socket
                   FROM products p
                   LEFT JOIN brands b ON b.brand_id = p.brand_id
                   INNER JOIN categories c ON c.category_id = p.category_id
                   LEFT JOIN product_images image ON image.product_id = p.product_id AND image.display_order = 1
                   WHERE ' . implode(' AND ', $where) . '
                   ORDER BY p.created_at DESC LIMIT :limit',
              );
              foreach ($params as $key => $value) {
                  $statement->bindValue($key, $value);
              }
              $statement->bindValue(':limit', max(1, min($limit, 100)), PDO::PARAM_INT);
              $statement->execute();
      
              return $statement->fetchAll();
          }
      }
       M index.html
      ?? "assets/img/Banner KM/"

## Activity

    $ "C:\\Windows\\System32\\WindowsPowerShell\\v1.0\\powershell.exe" -Command "rg -l '<header|class=\"header\"|class=\"site-header\"' -g '*.html'; rg -n -C 3 'featured-products-list' index.html; Get-Content about.html -TotalCount 105; Get-Content js/csv-parser.js"
    status: Completed · exit 0
      392-							<i class="bi bi-arrow-up-right"></i>
      393-						</a>
      394-					</div>
      395:					<div class="row g-4" id="featured-products-list">
      396-						<div class="col-sm-6 col-lg-3 d-flex">
      397-							<article class="product-card d-flex w-100 flex-column">
      398-								<div class="product-image">
      <!doctype html>
      <html lang="vi">
      	<head>
      		<meta charset="utf-8" />
      		<meta name="viewport" content="width=device-width, initial-scale=1" />
      		<title>Vá» TNC | TNC Store</title>
      		<link rel="stylesheet" href="css/style.css" />
      		<link rel="icon" href="assets/favicon.png" />
      		<link
      			href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
      			rel="stylesheet"
      		/>
      		<link
      			rel="stylesheet"
      			href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
      		/>
      	</head>
      	<body>
      		<header class="site-header">
      			<div class="container">
      				<nav class="navbar navbar-expand-lg py-3" aria-label="Main navigation">
      					<a class="navbar-brand logo" href="index.html" aria-label="TNC Store home">
      						<img src="assets/img/branding/tnc.png" alt="TNC Store" />
      					</a>
      					<button
      						class="navbar-toggler"
      						type="button"
      						data-bs-toggle="collapse"
      						data-bs-target="#storeNavigation"
      						aria-controls="storeNavigation"
      						aria-expanded="false"
      						aria-label="Toggle navigation"
      					>
      						<span class="navbar-toggler-icon"></span>
      					</button>
      					<div class="collapse navbar-collapse" id="storeNavigation">
      						<div class="dropdown category-dropdown my-3 my-lg-0">
      							<button
      								class="category-toggle dropdown-toggle"
      								type="button"
      								data-bs-toggle="dropdown"
      								aria-expanded="false"
      							>
      								<i class="bi bi-grid-3x3-gap me-2"></i>
      								Danh má»¥c
      							</button>
      							<ul class="dropdown-menu">
      								<li>
      									<a class="dropdown-item" href="buildpc.html">Build PC</a>
      								</li>
      								<li>
      									<a class="dropdown-item" href="products.html?category=pc">
      										PC & Linh kiá»‡n
      									</a>
      								</li>
      								<li>
      									<a class="dropdown-item" href="products.html?category=keyboard">
      										BÃ n phÃ­m
      									</a>
      								</li>
      								<li>
      									<a class="dropdown-item" href="products.html?category=monitor">
      										MÃ n hÃ¬nh
      									</a>
      								</li>
      								<li>
      									<a
      										class="dropdown-item"
      										href="products.html?category=gaming-gear"
      									>
      										Gaming gear
      									</a>
      								</li>
      							</ul>
      						</div>
      						<form
      							class="search-form mx-lg-4 my-3 my-lg-0"
      							role="search"
      							action="products.html"
      							method="get"
      						>
      							<label class="visually-hidden" for="site-search">Search products</label>
      							<input
      								id="site-search"
      								class="form-control"
      								type="search"
      								name="q"
      								placeholder="Báº¡n Ä‘ang tÃ¬m gÃ¬?"
      								autocomplete="off"
      							/>
      							<button class="search-button" type="submit" aria-label="Search">
      								<i class="bi bi-search" aria-hidden="true"></i>
      							</button>
      						</form>
      						<div class="header-actions ms-lg-auto">
      							<a class="header-action header-button" href="index.html#accountModal">
      								<i class="bi bi-person-circle" aria-hidden="true"></i>
      								<span>TÃ i khoáº£n</span>
      							</a>
      							<a class="header-action cart-action" href="cart.html">
      								<span class="cart-icon">
      									<i class="bi bi-cart3" aria-hidden="true"></i>
      									<b>0</b>
      								</span>
      								<span>Giá» hÃ ng</span>
      /**
       * Utility to parse CSV files and convert model names to URL slugs
       */
      
      const FALLBACK_PRODUCTS = [
      	{
      		HÃ£ng: "Intel",
      		Model: "Core i5-12400F",
      		Socket: "LGA1700",
      		"Sá»‘ nhÃ¢n/luá»“ng": "6 nhÃ¢n 12 luá»“ng",
      		"Xung nhá»‹p": "Up to 4.4 GHz",
      		TDP: "65W",
      		iGPU: "KhÃ´ng cÃ³",
      		"GiÃ¡ TB (VNÄ)": "3.200.000 Ä‘",
      		áº¢nh: "12400F-1.webp|12400F-2.webp",
      	},
      	{
      		HÃ£ng: "Intel",
      		Model: "Core i7-14700K",
      		Socket: "LGA1700",
      		"Sá»‘ nhÃ¢n/luá»“ng": "20 nhÃ¢n 28 luá»“ng",
      		"Xung nhá»‹p": "Up to 5.6 GHz",
      		TDP: "125W",
      		iGPU: "Intel UHD 770",
      		"GiÃ¡ TB (VNÄ)": "10.500.000 Ä‘",
      		áº¢nh: "14700K-1.webp|14700K-2.webp",
      	},
      	{
      		HÃ£ng: "Intel",
      		Model: "Core Ultra 7 265K",
      		Socket: "LGA1851",
      		"Sá»‘ nhÃ¢n/luá»“ng": "20 nhÃ¢n 20 luá»“ng",
      		"Xung nhá»‹p": "Up to 5.5 GHz",
      		TDP: "125W",
      		iGPU: "Intel Graphics (4 Xe-cores)",
      		"GiÃ¡ TB (VNÄ)": "10.200.000 Ä‘",
      		áº¢nh: "265K-1.jpg|265K-2.jpg",
      	},
      ];
      
      function toSlug(str) {
      	if (!str) return "";
      	return str
      		.toString()
      		.toLowerCase()
      		.trim()
      		.normalize("NFD")
      		.replace(/[\u0300-\u036f]/g, "")
      		.replace(/[Ä‘Ä]/g, "d")
      		.replace(/[^a-z0-9 -]/g, "")
      		.replace(/\s+/g, "-")
      		.replace(/-+/g, "-");
      }
      
      async function fetchAndParseCSV(csvPath, fallbackProducts = FALLBACK_PRODUCTS) {
      	try {
      		const response = await fetch(csvPath);
      		if (!response.ok) return fallbackProducts;
      		const text = await response.text();
      		const parsed = parseCSVText(text);
      		return parsed.length > 0 ? parsed : fallbackProducts;
      	} catch (err) {
      		return fallbackProducts;
      	}
      }
      
      function parseCSVText(text) {
      	const lines = text
      		.split(/\r?\n/)
      		.map((l) => l.trim())
      		.filter(Boolean);
      	if (lines.length < 2) return [];
      	const headers = parseCSVLine(lines[0]);
      	return lines.slice(1).map((line) => {
      		const values = parseCSVLine(line);
      		return Object.fromEntries(headers.map((h, i) => [h.trim(), (values[i] || "").trim()]));
      	});
      }
      
      function parseCSVLine(line) {
      	return [...line.matchAll(/("([^"]*)")|([^,]+)|(?<=,)(?=,|$)/g)].map((m) => m[2] ?? m[3] ?? "");
      }

## Activity

    $ "C:\\Windows\\System32\\WindowsPowerShell\\v1.0\\powershell.exe" -Command "rg -n 'js/header\\.js|<header|class=\"header\"' -g '*.html' .; Get-Content css/style.css | Select-Object -Skip 1860 -First 65; Get-Content index.html | Select-Object -Skip 385 -First 62; Get-Content js/account.js -TotalCount 55; Get-Content js/cart.js -TotalCount 40"
    status: Completed · exit 0
      .\about.html:19:		<header class="site-header">
      .\404.html:19:		<header class="site-header">
      .\buildpc.html:23:		<header class="site-header">
      .\cart.html:19:		<header class="site-header">
      .\admin.html:58:			<header class="admin-topbar">
      .\checkout.html:19:		<header class="site-header">
      .\contact.html:19:		<header class="site-header">
      .\index.html:813:		<script src="js/header.js"></script>
      .\forgot-password.html:19:		<header class="site-header">
      .\login.html:19:		<header class="site-header">
      .\news-post.html:19:		<header class="site-header">
      .\products.html:19:		<header class="site-header">
      .\product-detail.html:19:		<header class="site-header">
      .\news.html:19:		<header class="site-header">
      .\news.html:95:				<header class="news-page-header">
      .\profile.html:18:		<header class="site-header">
      .\register.html:19:		<header class="site-header">
      	display: inline-flex;
      }
      
      .cart-icon b {
      	position: absolute;
      	top: -7px;
      	right: -9px;
      	display: grid;
      	width: 17px;
      	height: 17px;
      	place-items: center;
      	border-radius: 50%;
      	background: var(--header-accent);
      	color: #fff;
      	font-size: 0.65rem;
      }
      
      /* Responsive adjustments */
      @media (max-width: 991.98px) {
      	.header-actions {
      		justify-content: flex-end;
      		padding-bottom: 0.25rem;
      	}
      	.category-grid {
      		grid-template-columns: repeat(2, 1fr);
      	}
      	.trust-strip {
      		grid-template-columns: 1fr;
      	}
      	.buildpc-intro-copy {
      		width: 70%;
      	}
      	.buildpc-intro-art {
      		right: 2%;
      	}
      }
      
      @media (max-width: 575.98px) {
      	.header-actions {
      		justify-content: space-between;
      		gap: 0.75rem;
      	}
      
      	.header-action {
      		font-size: 0.78rem;
      	}
      	.buildpc-intro > .container {
      		min-height: 520px;
      		align-items: start;
      	}
      	.buildpc-intro-copy {
      		width: 100%;
      		padding: 4rem 1rem 13rem;
      	}
      	.buildpc-intro-art {
      		right: 50%;
      		bottom: 2rem;
      		width: 210px;
      		height: 210px;
      		transform: translateX(50%) rotate(8deg);
      	}
      	.buildpc-toolbar {
      		align-items: start;
      		flex-direction: column;
      		gap: 1rem;
      						<div>
      							<p class="eyebrow">ÄÆ°á»£c yÃªu thÃ­ch tuáº§n nÃ y</p>
      							<h2>Sáº£n pháº©m ná»•i báº­t</h2>
      						</div>
      						<a href="products.html" class="text-link">
      							Xem cá»­a hÃ ng
      							<i class="bi bi-arrow-up-right"></i>
      						</a>
      					</div>
      					<div class="row g-4" id="featured-products-list">
      						<div class="col-sm-6 col-lg-3 d-flex">
      							<article class="product-card d-flex w-100 flex-column">
      								<div class="product-image">
      									<i class="bi bi-cpu"></i>
      									<span class="product-tag">BÃ¡n cháº¡y</span>
      								</div>
      								<p class="product-brand">Intel Â· Socket LGA1700</p>
      								<h3>
      									<a
      										href="product-detail.html?model=Core%20i5-12400F"
      										class="text-decoration-none text-dark"
      									>
      										CPU Intel Core i5-12400F
      									</a>
      								</h3>
      								<strong class="product-price">3.200.000 Ä‘</strong>
      								<button class="btn btn-outline-primary w-100 mt-3">
      									<i class="bi bi-cart-plus me-2"></i>
      									ThÃªm vÃ o giá»
      								</button>
      							</article>
      						</div>
      					</div>
      				</div>
      			</section>
      			<div class="container my-5">
        			  <!-- TiÃªu Ä‘á» pháº§n khuyáº¿n mÃ£i -->
         				 <div class="d-flex align-items-center mb-4">
             			 <h4 class="fw-bold text-uppercase border-start border-4 border-primary ps-3 m-0">ChuyÃªn trang khuyáº¿n mÃ£i</h4>
         			 </div>
         			 <!-- HÃ€NG THá»¨ NHáº¤T -->
         			 <div class="row g-4 mb-4">
              		<!-- Banner 1 -->
             			 <div class="col-lg-6 col-md-12">
               		   <div class="overflow-hidden shadow-sm h-100 hover-zoom">
                   	   <img src="assets/img/Banner KM/anh1.jpg" class="card-img object-fit-cover" style="height: 320px;" alt="Khuyáº¿n mÃ£i 1">
                  </div>
              </div>
             	 <!-- Banner 2 -->
              		<div class="col-lg-6 col-md-12">
                  	<div class="overflow-hidden shadow-sm h-100 hover-zoom">
                      <img src="assets/img/Banner KM/anh2.jpg" class="card-img object-fit-cover" style="height: 320px;" alt="Khuyáº¿n mÃ£i 2">
                  </div>
              </div>
          </div>
          		<!-- HÃ€NG THá»¨ HAI -->
          		<div class="row g-4">
             		 <!-- Banner 3 -->
              		<div class="col-lg-6 col-md-12">
                 		 <div class="overflow-hidden shadow-sm h-100 hover-zoom">
                      <img src="assets/img/Banner KM/anh3.jpg" class="card-img object-fit-cover" style="height: 320px;" alt="Khuyáº¿n mÃ£i 3">
                  </div>
      document.addEventListener("DOMContentLoaded", () => {
      	const modalElement = document.querySelector("#accountModal");
      	if (!modalElement || typeof bootstrap === "undefined") return;
      
      	if (
      		window.location.hash === "#accountModal" ||
      		window.location.hash === "#accountModal-register"
      	) {
      		if (window.location.hash === "#accountModal-register") {
      			const registerTab = modalElement.querySelector(
      				'[data-bs-target="#account-register-pane"]',
      			);
      			registerTab && bootstrap.Tab.getOrCreateInstance(registerTab).show();
      		}
      		bootstrap.Modal.getOrCreateInstance(modalElement).show();
      	}
      });
      const cartStorageKey = "tnc-cart";
      
      function getCart() {
      	try {
      		return JSON.parse(localStorage.getItem(cartStorageKey)) || [];
      	} catch {
      		return [];
      	}
      }
      
      function saveCart(cart) {
      	localStorage.setItem(cartStorageKey, JSON.stringify(cart));
      }
      
      function formatPrice(value) {
      	return `${value.toLocaleString("vi-VN")}Ä‘`;
      }
      
      function getProductFromCard(card) {
      	const priceText = card.querySelector(".product-price")?.textContent || "0";
      	const icon = card.querySelector(".product-image i")?.className || "bi bi-box";
      	const imageClass = card.querySelector(".product-image")?.className || "product-image";
      
      	return {
      		id:
      			card.querySelector("h3")?.textContent.trim().toLowerCase().replace(/\s+/g, "-") ||
      			Date.now().toString(),
      		name: card.querySelector("h3")?.textContent.trim() || "Sáº£n pháº©m",
      		brand: card.querySelector(".product-brand")?.textContent.trim() || "TNC STORE",
      		price: Number(priceText.replace(/[^\d]/g, "")),
      		icon,
      		imageClass,
      		quantity: 1,
      	};
      }
      
      function updateCartCount() {
      	const count = getCart().reduce((total, item) => total + item.quantity, 0);
      	document.querySelectorAll(".cart-icon b").forEach((counter) => {
      		counter.textContent = count;

## Activity

    $ "C:\\Windows\\System32\\WindowsPowerShell\\v1.0\\powershell.exe" -Command "rg -n -C 2 '</body>|<script' -g '*.html' about.html 404.html buildpc.html cart.html checkout.html contact.html forgot-password.html login.html news-post.html news.html product-detail.html products.html profile.html register.html index.html; Get-Content buildpc.html -TotalCount 115; Get-Content 404.html -TotalCount 115"
    status: Completed · exit 0
      forgot-password.html-174-			</div>
      forgot-password.html-175-		</footer>
      forgot-password.html:176:		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
      forgot-password.html:177:		<script src="js/cart.js"></script>
      forgot-password.html:178:	</body>
      forgot-password.html-179-</html>
      --
      contact.html-124-			</section>
      contact.html-125-		</main>
      contact.html:126:		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
      contact.html-127-		<footer class="site-footer">
      contact.html-128-			<div class="container">
      --
      contact.html-167-			</div>
      contact.html-168-		</footer>
      contact.html:169:	</body>
      contact.html-170-</html>
      --
      cart.html-84-			</section>
      cart.html-85-		</main>
      cart.html:86:		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
      cart.html:87:		<script src="js/cart.js"></script>
      cart.html-88-		<footer class="site-footer">
      cart.html-89-			<div class="container">
      --
      cart.html-128-			</div>
      cart.html-129-		</footer>
      cart.html:130:	</body>
      cart.html-131-</html>
      --
      products.html-164-			</div>
      products.html-165-		</footer>
      products.html:166:		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
      products.html:167:		<script src="js/csv-parser.js"></script>
      products.html:168:		<script src="js/products.js"></script>
      products.html:169:		<script src="js/cart.js"></script>
      products.html:170:	</body>
      products.html-171-</html>
      --
      register.html-187-			</div>
      register.html-188-		</footer>
      register.html:189:		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
      register.html:190:		<script src="js/cart.js"></script>
      register.html:191:	</body>
      register.html-192-</html>
      --
      product-detail.html-251-		</footer>
      product-detail.html-252-
      product-detail.html:253:		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
      product-detail.html:254:		<script src="js/csv-parser.js"></script>
      product-detail.html:255:		<script src="js/cart.js"></script>
      product-detail.html:256:		<script src="js/product-detail.js"></script>
      product-detail.html:257:	</body>
      product-detail.html-258-</html>
      --
      index.html-25-			rel="stylesheet"
      index.html-26-		/>
      index.html:27:		<script
      index.html-28-			src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
      index.html-29-			integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
      --
      index.html-807-			</div>
      index.html-808-		</footer>
      index.html:809:		<script src="js/csv-parser.js"></script>
      index.html:810:		<script src="js/home.js"></script>
      index.html:811:		<script src="js/cart.js"></script>
      index.html:812:		<script src="js/account.js"></script>
      index.html:813:		<script src="js/header.js"></script>
      index.html:814:	</body>
      index.html-815-</html>
      --
      checkout.html-191-			</section>
      checkout.html-192-		</main>
      checkout.html:193:		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
      checkout.html:194:		<script src="js/cart.js"></script>
      checkout.html:195:		<script src="js/checkout.js"></script>
      checkout.html-196-		<footer class="site-footer">
      checkout.html-197-			<div class="container">
      --
      checkout.html-236-			</div>
      checkout.html-237-		</footer>
      checkout.html:238:	</body>
      checkout.html-239-</html>
      --
      404.html-42-			</div>
      404.html-43-		</main>
      404.html:44:		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
      404.html-45-		<footer class="site-footer">
      404.html-46-			<div class="container">
      --
      404.html-85-			</div>
      404.html-86-		</footer>
      404.html:87:	</body>
      404.html-88-</html>
      --
      profile.html-174-			</section>
      profile.html-175-		</main>
      profile.html:176:		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
      profile.html:177:		<script src="js/cart.js"></script>
      profile.html:178:	</body>
      profile.html-179-</html>
      --
      news-post.html-112-			</div>
      news-post.html-113-		</footer>
      news-post.html:114:		<script src="js/cart.js"></script>
      news-post.html:115:	</body>
      news-post.html-116-</html>
      --
      login.html-178-			</div>
      login.html-179-		</footer>
      login.html:180:		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
      login.html:181:		<script src="js/cart.js"></script>
      login.html:182:	</body>
      login.html-183-</html>
      --
      news.html-289-			</div>
      news.html-290-		</footer>
      news.html:291:		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
      news.html:292:		<script src="js/cart.js"></script>
      news.html:293:	</body>
      news.html-294-</html>
      --
      about.html-165-			</section>
      about.html-166-		</main>
      about.html:167:		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
      about.html-168-		<footer class="site-footer">
      about.html-169-			<div class="container">
      --
      about.html-202-			</div>
      about.html-203-		</footer>
      about.html:204:	</body>
      about.html-205-</html>
      --
      buildpc.html-285-			</div>
      buildpc.html-286-		</div>
      buildpc.html:287:		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
      buildpc.html:288:		<script src="js/buildpc.js"></script>
      buildpc.html:289:		<script src="js/cart.js"></script>
      buildpc.html-290-		<footer class="site-footer">
      buildpc.html-291-			<div class="container">
      --
      buildpc.html-330-			</div>
      buildpc.html-331-		</footer>
      buildpc.html:332:	</body>
      buildpc.html-333-</html>
      <!doctype html>
      <html lang="vi">
      	<head>
      		<meta charset="utf-8" />
      		<meta name="viewport" content="width=device-width, initial-scale=1" />
      		<meta
      			name="description"
      			content="Tá»± chá»n linh kiá»‡n vÃ  xÃ¢y dá»±ng cáº¥u hÃ¬nh PC phÃ¹ há»£p táº¡i TNC Store."
      		/>
      		<title>Build PC | TNC Store</title>
      		<link rel="stylesheet" href="css/style.css" />
      		<link rel="icon" href="assets/favicon.png" />
      		<link
      			href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
      			rel="stylesheet"
      		/>
      		<link
      			rel="stylesheet"
      			href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
      		/>
      	</head>
      	<body class="buildpc-body">
      		<header class="site-header">
      			<div class="container">
      				<nav class="navbar navbar-expand-lg py-3" aria-label="Main navigation">
      					<a class="navbar-brand logo" href="index.html">
      						<img src="assets/img/branding/tnc.png" alt="TNC Store" />
      					</a>
      					<button
      						class="navbar-toggler"
      						type="button"
      						data-bs-toggle="collapse"
      						data-bs-target="#storeNavigation"
      						aria-controls="storeNavigation"
      						aria-expanded="false"
      						aria-label="Toggle navigation"
      					>
      						<span class="navbar-toggler-icon"></span>
      					</button>
      					<div class="collapse navbar-collapse" id="storeNavigation">
      						<a class="buildpc-nav-link mx-lg-4 my-3 my-lg-0" href="products.html">
      							<i class="bi bi-box-seam me-2"></i>
      							Sáº£n pháº©m
      						</a>
      						<form
      							class="search-form my-3 my-lg-0"
      							role="search"
      							action="products.html"
      							method="get"
      						>
      							<label class="visually-hidden" for="buildpc-search">
      								TÃ¬m kiáº¿m sáº£n pháº©m
      							</label>
      							<input
      								id="buildpc-search"
      								class="form-control"
      								type="search"
      								name="q"
      								placeholder="Báº¡n Ä‘ang tÃ¬m gÃ¬?"
      								autocomplete="off"
      							/>
      							<button class="search-button" type="submit" aria-label="TÃ¬m kiáº¿m">
      								<i class="bi bi-search" aria-hidden="true"></i>
      							</button>
      						</form>
      						<div class="header-actions ms-lg-auto">
      							<a class="header-action" href="contact.html">
      								<i class="bi bi-headset" aria-hidden="true"></i>
      								<span>TÆ° váº¥n</span>
      							</a>
      							<a class="header-action cart-action" href="cart.html">
      								<span class="cart-icon">
      									<i class="bi bi-cart3" aria-hidden="true"></i>
      									<b>0</b>
      								</span>
      								<span>Giá» hÃ ng</span>
      							</a>
      						</div>
      					</div>
      				</nav>
      			</div>
      		</header>
      
      		<main>
      			<section class="buildpc-intro">
      				<div class="container">
      					<div class="buildpc-intro-copy">
      						<p class="eyebrow">TNC Build / Tá»± chá»n linh kiá»‡n</p>
      						<h1>
      							XÃ¢y cáº¥u hÃ¬nh
      							<br />
      							<span>Ä‘Ãºng nhu cáº§u.</span>
      						</h1>
      						<p>
      							Chá»n tá»«ng linh kiá»‡n, theo dÃµi ngÃ¢n sÃ¡ch vÃ  kiá»ƒm tra tÆ°Æ¡ng thÃ­ch ngay
      							trong lÃºc build. Cáº¥u hÃ¬nh cá»§a báº¡n sáº½ Ä‘Æ°á»£c lÆ°u trÃªn trÃ¬nh duyá»‡t nÃ y.
      						</p>
      					</div>
      					<div class="buildpc-intro-art" aria-hidden="true">
      						<i class="bi bi-cpu"></i>
      						<span>
      							BUILD
      							<br />
      							<b>YOUR WAY</b>
      						</span>
      					</div>
      				</div>
      			</section>
      
      			<section class="section-space buildpc-workspace">
      				<div class="container">
      					<div class="buildpc-toolbar">
      						<div>
      							<p class="eyebrow">Bá»™ linh kiá»‡n</p>
      							<h2>Chá»n pháº§n cá»©ng</h2>
      <!doctype html>
      <html lang="vi">
      	<head>
      		<meta charset="utf-8" />
      		<meta name="viewport" content="width=device-width, initial-scale=1" />
      		<title>KhÃ´ng tÃ¬m tháº¥y | TNC Store</title>
      		<link rel="stylesheet" href="css/style.css" />
      		<link rel="icon" href="assets/favicon.png" />
      		<link
      			href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
      			rel="stylesheet"
      		/>
      		<link
      			rel="stylesheet"
      			href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
      		/>
      	</head>
      	<body>
      		<header class="site-header">
      			<div class="container">
      				<nav class="navbar py-3">
      					<a class="navbar-brand logo" href="index.html">
      						<img src="assets/img/branding/tnc.png" alt="TNC Store" />
      					</a>
      				</nav>
      			</div>
      		</header>
      		<main class="error-page">
      			<div class="container">
      				<div class="error-number">404</div>
      				<p class="eyebrow">CÃ³ váº» báº¡n Ä‘Ã£ Ä‘i hÆ¡i xa</p>
      				<h1>
      					Trang nÃ y khÃ´ng
      					<br />
      					á»Ÿ Ä‘Ã¢y ná»¯a.
      				</h1>
      				<p>ÄÆ°á»ng dáº«n cÃ³ thá»ƒ Ä‘Ã£ thay Ä‘á»•i. HÃ£y quay vá» cá»­a hÃ ng Ä‘á»ƒ tiáº¿p tá»¥c khÃ¡m phÃ¡.</p>
      				<a class="btn btn-primary" href="index.html">
      					Vá» trang chá»§
      					<i class="bi bi-arrow-right ms-2"></i>
      				</a>
      			</div>
      		</main>
      		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
      		<footer class="site-footer">
      			<div class="container">
      				<div class="row g-4">
      					<div class="col-lg-5">
      						<a class="logo" href="index.html">
      							<img src="assets/img/branding/tnc.png" alt="TNC Store" />
      						</a>
      						<p>Thiáº¿t bá»‹ cÃ´ng nghá»‡ chá»n lá»c cho nhá»¯ng ngÆ°á»i luÃ´n muá»‘n lÃ m tá»‘t hÆ¡n.</p>
      					</div>
      					<div class="col-6 col-lg-2">
      						<h3>KhÃ¡m phÃ¡</h3>
      						<a href="products.html">Sáº£n pháº©m</a>
      						<a href="about.html">Vá» TNC</a>
      						<a href="contact.html">LiÃªn há»‡</a>
      					</div>
      					<div class="col-6 col-lg-2">
      						<h3>Há»— trá»£</h3>
      						<a href="cart.html">Giá» hÃ ng</a>
      						<a href="payment.html">Thanh toÃ¡n</a>
      						<a href="404.html">Tra cá»©u Ä‘Æ¡n</a>
      					</div>
      					<div class="col-lg-3">
      						<h3>ÄÄƒng kÃ½ nháº­n tin</h3>
      						<p>Æ¯u Ä‘Ã£i má»›i, cáº­p nháº­t sáº£n pháº©m má»›i má»—i tuáº§n.</p>
      						<form class="newsletter">
      							<input
      								type="email"
      								placeholder="Email cá»§a báº¡n"
      								aria-label="Email cá»§a báº¡n"
      							/>
      							<button type="submit" aria-label="Subscribe">
      								<i class="bi bi-arrow-right"></i>
      							</button>
      						</form>
      					</div>
      				</div>
      				<div class="footer-bottom">
      					Â© 2026 TNC Store
      					<a href="admin.html">Quáº£n trá»‹ viÃªn</a>
      				</div>
      			</div>
      		</footer>
      	</body>
      </html>

## Activity

    $ "C:\\Windows\\System32\\WindowsPowerShell\\v1.0\\powershell.exe" -Command 'Get-Content js/cart.js; Get-Content css/style.css | Select-Object -Skip 85 -First 195; Get-Content .htaccess; Get-Content backend/views/legacy-page.php'
    status: Completed · exit 0
      const cartStorageKey = "tnc-cart";
      
      function getCart() {
      	try {
      		return JSON.parse(localStorage.getItem(cartStorageKey)) || [];
      	} catch {
      		return [];
      	}
      }
      
      function saveCart(cart) {
      	localStorage.setItem(cartStorageKey, JSON.stringify(cart));
      }
      
      function formatPrice(value) {
      	return `${value.toLocaleString("vi-VN")}Ä‘`;
      }
      
      function getProductFromCard(card) {
      	const priceText = card.querySelector(".product-price")?.textContent || "0";
      	const icon = card.querySelector(".product-image i")?.className || "bi bi-box";
      	const imageClass = card.querySelector(".product-image")?.className || "product-image";
      
      	return {
      		id:
      			card.querySelector("h3")?.textContent.trim().toLowerCase().replace(/\s+/g, "-") ||
      			Date.now().toString(),
      		name: card.querySelector("h3")?.textContent.trim() || "Sáº£n pháº©m",
      		brand: card.querySelector(".product-brand")?.textContent.trim() || "TNC STORE",
      		price: Number(priceText.replace(/[^\d]/g, "")),
      		icon,
      		imageClass,
      		quantity: 1,
      	};
      }
      
      function updateCartCount() {
      	const count = getCart().reduce((total, item) => total + item.quantity, 0);
      	document.querySelectorAll(".cart-icon b").forEach((counter) => {
      		counter.textContent = count;
      	});
      }
      
      function addToCart(product) {
      	const cart = getCart();
      	const existingProduct = cart.find((item) => item.id === product.id);
      
      	if (existingProduct) {
      		existingProduct.quantity += 1;
      	} else {
      		cart.push(product);
      	}
      
      	saveCart(cart);
      	updateCartCount();
      }
      
      function renderCartPage() {
      	const cartList = document.querySelector("[data-cart-list]");
      	if (!cartList) return;
      
      	const cart = getCart();
      	const emptyMessage = document.querySelector("[data-cart-empty]");
      	const cartContent = document.querySelector("[data-cart-content]");
      
      	if (cart.length === 0) {
      		cartList.innerHTML = "";
      		emptyMessage?.classList.remove("d-none");
      		cartContent?.classList.add("d-none");
      		updateCartSummary(0);
      		return;
      	}
      
      	emptyMessage?.classList.add("d-none");
      	cartContent?.classList.remove("d-none");
      	cartList.innerHTML = cart
      		.map(
      			(item) => `
      				<div class="cart-item" data-cart-id="${item.id}">
      					<div class="cart-product-image ${item.imageClass.replace("product-image", "")}">
      						<i class="${item.icon}"></i>
      					</div>
      					<div class="flex-grow-1">
      						<p class="product-brand">${item.brand}</p>
      						<h2>${item.name}</h2>
      						<p class="cart-meta">Sáº£n pháº©m chÃ­nh hÃ£ng</p>
      					</div>
      					<div class="quantity-control">
      						<button type="button" data-cart-action="decrease" aria-label="Giáº£m sá»‘ lÆ°á»£ng">âˆ’</button>
      						<span>${item.quantity}</span>
      						<button type="button" data-cart-action="increase" aria-label="TÄƒng sá»‘ lÆ°á»£ng">+</button>
      					</div>
      					<strong>${formatPrice(item.price * item.quantity)}</strong>
      					<button type="button" class="remove-item" data-cart-action="remove" aria-label="XÃ³a sáº£n pháº©m">
      						<i class="bi bi-x-lg"></i>
      					</button>
      				</div>
      			`,
      		)
      		.join("");
      
      	updateCartSummary(cart.reduce((total, item) => total + item.price * item.quantity, 0));
      }
      
      function updateCartSummary(total) {
      	document.querySelectorAll("[data-cart-subtotal], [data-cart-total]").forEach((element) => {
      		element.textContent = formatPrice(total);
      	});
      }
      
      function renderCheckoutPage() {
      	const itemsContainer = document.querySelector("[data-checkout-items]");
      	if (!itemsContainer) return;
      
      	const cart = getCart();
      	const submitButton = document.querySelector("[data-checkout-submit]");
      	const total = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
      
      	if (cart.length === 0) {
      		itemsContainer.innerHTML = '<p class="cart-meta">Giá» hÃ ng Ä‘ang trá»‘ng.</p>';
      		if (submitButton) submitButton.disabled = true;
      	} else {
      		itemsContainer.innerHTML = cart
      			.map(
      				(item) => `
      					<div class="mini-product">
      						<span>
      							<i class="${item.icon}"></i>
      							${item.name}
      							<b>Ã— ${item.quantity}</b>
      						</span>
      						<strong>${formatPrice(item.price * item.quantity)}</strong>
      					</div>
      				`,
      			)
      			.join("");
      	}
      
      	if (submitButton && cart.length > 0) submitButton.disabled = false;
      	updateCartSummary(total);
      }
      
      document.addEventListener("click", (event) => {
      	const addButton = event.target.closest(".product-card button, .idea-product button");
      	if (addButton) {
      		addToCart(getProductFromCard(addButton.closest(".product-card")));
      		const originalText = addButton.innerHTML;
      		addButton.innerHTML = '<i class="bi bi-check2 me-2"></i>ÄÃ£ thÃªm';
      		setTimeout(() => {
      			addButton.innerHTML = originalText;
      		}, 1200);
      		return;
      	}
      
      	const actionButton = event.target.closest("[data-cart-action]");
      	if (!actionButton) return;
      
      	const cartItem = actionButton.closest("[data-cart-id]");
      	const productId = cartItem?.dataset.cartId;
      	const cart = getCart();
      	const product = cart.find((item) => item.id === productId);
      	if (!product) return;
      
      	if (actionButton.dataset.cartAction === "increase") product.quantity += 1;
      	if (actionButton.dataset.cartAction === "decrease") product.quantity -= 1;
      	if (actionButton.dataset.cartAction === "remove" || product.quantity <= 0) {
      		cart.splice(cart.indexOf(product), 1);
      	}
      
      	saveCart(cart);
      	updateCartCount();
      	renderCartPage();
      });
      
      document.addEventListener("DOMContentLoaded", () => {
      	updateCartCount();
      	renderCartPage();
      	renderCheckoutPage();
      });
      	margin: 0 auto;
      	position: relative;
      }
      /* Header */
      .header {
      	width: 100%;
      	background: #29324e;
      }
      .header .header-fixed {
      	position: fixed;
      	z-index: 999;
      	top: 0;
      }
      .header .header-top {
      	background-color: var(--color-global);
      	padding: 10px 0;
      	text-align: right;
      }
      .header .header-top .item {
      	border-right: 2px solid #2860c5;
      	padding: 0 15px;
      }
      .header .header-top .item:last-child {
      	border: none;
      	padding: 0 15px;
      }
      .header .header-top .item i {
      	margin-right: 5px;
      	color: #FFFFFF;
      }
      .header .header-top .item span {
      	color: #FFFFFF;
      	font-weight: 600;
      }
      .header .header-mid {
      	box-shadow: 0 2px 10px rgba(23, 33, 43, 0.05);
      	padding: 0 24px;
      }
      .header .container {
      	max-width: 1200px;
      }
      .header .header-bottom {
      
      }
      .logo {
      	display: inline-flex;
      	align-items: center;
      	gap: 9px;
      	color: var(--header-ink);
      	font-weight: 800;
      	letter-spacing: 0.04em;
      	line-height: 1;
      	text-decoration: none;
      	white-space: nowrap;
      }
      
      .logo:hover {
      	color: var(--header-accent);
      }
      
      .logo img {
      	max-width: 10rem;
      	height: auto;
      	object-fit: contain;
      }
      
      .search-form {
      	position: relative;
      	flex: 1 1 460px;
      }
      
      .category-dropdown {
      	flex: 0 0 auto;
      }
      /* Home carousel */
      
      .category-toggle {
      	min-height: 44px;
      	padding: 0.65rem 1rem;
      	border: 1px solid #d9dee3;
      	border-radius: 6px;
      	background: #fff;
      	color: var(--header-ink);
      	font-size: 0.86rem;
      	font-weight: 700;
      	white-space: nowrap;
      }
      
      .category-toggle:hover,
      .category-toggle:focus,
      .category-toggle.show {
      	border-color: var(--header-accent);
      	background: #f5f7fa;
      	color: var(--header-accent);
      }
      
      .category-dropdown .dropdown-menu {
      	min-width: 220px;
      	margin-top: 0.5rem;
      	padding: 0.5rem;
      	border: 1px solid var(--header-border);
      	border-radius: 4px;
      	box-shadow: 0 12px 28px rgba(23, 33, 43, 0.12);
      }
      
      .category-dropdown .dropdown-item {
      	padding: 0.7rem 0.8rem;
      	border-radius: 3px;
      	color: var(--header-ink);
      	font-size: 0.84rem;
      }
      
      .category-dropdown .dropdown-item:hover,
      .category-dropdown .dropdown-item:focus {
      	background: #eef1f6;
      	color: var(--header-accent);
      }
      
      .search-form .form-control {
      	min-height: 44px;
      	padding: 0.65rem 3.2rem 0.65rem 1rem;
      	border: 1px solid #d9dee3;
      	border-radius: 6px;
      	background: #f8f9fa;
      }
      
      .search-form .form-control:focus {
      	border-color: var(--header-accent);
      	box-shadow: 0 0 0 2px rgba(41, 51, 79, 0.14);
      }
      
      .search-button {
      	position: absolute;
      	top: 4px;
      	right: 4px;
      	width: 36px;
      	height: 36px;
      	border: 0;
      	border-radius: 5px;
      	background: var(--header-accent);
      	color: #fff;
      	font-size: 1rem;
      }
      
      .header-actions {
      	display: flex;
      	align-items: center;
      	gap: clamp(1rem, 2.5vw, 2rem);
      }
      
      .header-action {
      	display: inline-flex;
      	align-items: center;
      	gap: 9px;
      	color: #FFFFFF;
      	font-size: 0.85rem;
      	font-weight: 600;
      	line-height: 1.2;
      	text-decoration: none;
      	white-space: nowrap;
      }
      
      .header-button {
      	border: 0;
      	background: transparent;
      	padding: 0;
      }
      
      .header-action > i,
      .cart-icon > i {
      	color: #FFFFFF;
      	font-size: 1.65rem;
      }
      
      .header-action small {
      	display: block;
      	color: #68737d;
      	font-size: 0.7rem;
      	font-weight: 400;
      	margin-bottom: 2px;
      }
      
      /* Home carousel */
      .promo-slide {
      	color: #fff;
      }
      .promo-slide > .container {
      	position: relative;
      	display: flex;
      	align-items: center;
      }
      
      .promo-carousel-section .carousel-inner {
      	position: relative;
      }
      Options -Indexes
      DirectoryIndex index.php index.html
      
      RewriteEngine On
      RewriteCond %{THE_REQUEST} \s/+(.+)\.html[?\s] [NC]
      RewriteRule ^(.+)\.html$ /$1.php [R=302,L,NE]
      <?php
      declare(strict_types=1);
      
      if (!isset($legacyPage) || !is_string($legacyPage) || !preg_match('/^[a-z0-9-]+$/', $legacyPage)) {
          http_response_code(500);
          exit('Invalid PHP page entry point.');
      }
      
      $legacyPath = PROJECT_PATH . DIRECTORY_SEPARATOR . $legacyPage . '.html';
      if (!is_file($legacyPath)) {
          http_response_code(404);
          exit('Page not found.');
      }
      
      $html = (string) file_get_contents($legacyPath);
      $phpPages = [
          'index', 'about', 'admin', 'buildpc', 'cart', 'checkout', 'contact', 'forgot-password',
          'login', 'news', 'news-post', 'product-detail', 'products', 'profile', 'register', 'success', '404',
      ];
      foreach ($phpPages as $page) {
          $html = str_replace("{$page}.html", "{$page}.php", $html);
      }
      
      $clientConfig = '<script>window.TNC_API_BASE = "backend/api";</script>';
      $html = str_replace('</head>', $clientConfig . '</head>', $html);
      echo $html;

## Assistant

I found two separate issues behind the current result: only the home page has a partial three-row header, while the rest of the storefront uses an older one-row header; and the catalog utility contains a baked-in fallback product list in addition to the static home card. I’m consolidating the header so every storefront page receives the same three sections, and removing both fixture sources.

## Activity

    $ "C:\\Windows\\System32\\WindowsPowerShell\\v1.0\\powershell.exe" -Command 'Get-Content js/header.js; Get-Content js/csv-parser.js -TotalCount 6'
    status: Completed · exit 0
      window.addEventListener("scroll", () => {
          if (window.scrollY > 785) {
              document.querySelector(".header").classList.add("header-fixed");
          } else {
              document.querySelector(".header").classList.remove("header-fixed");
          }
      });
      /**
       * Utility to parse CSV files and convert model names to URL slugs
       */
      
      const FALLBACK_PRODUCTS = [
      	{

## Activity

    file changes: Completed · 1 changes
    Update { move_path: None }: C:\Users\Admin\Documents\Btl-php\js\header.js
    @@ -1,7 +1,30 @@
    -window.addEventListener("scroll", () => {
    -    if (window.scrollY > 785) {
    -        document.querySelector(".header").classList.add("header-fixed");
    -    } else {
    -        document.querySelector(".header").classList.remove("header-fixed");
    -    }
    -});
    \ No newline at end of file
    +/** Shared three-tier header for all legacy storefront pages. */
    +(() => {
    +	const headerMarkup = `
    +		<header class="store-header">
    +			<div class="header-top"><div class="container header-top-inner">
    +				<a href="products.html" class="top-catalog-link"><i class="bi bi-grid-3x3-gap"></i>Tất cả sản phẩm</a>
    +				<div class="header-contact"><a href="tel:0868302123"><i class="bi bi-telephone"></i>0868 302 123</a><a href="mailto:cskh@tncstore.vn"><i class="bi bi-envelope"></i>cskh@tncstore.vn</a></div>
    +			</div></div>
    +			<div class="header-mid"><div class="container header-mid-inner">
    +				<a class="navbar-brand logo" href="index.html" aria-label="TNC Store home"><img src="assets/img/branding/tnc.png" alt="TNC Store"></a>
    +				<div class="dropdown category-dropdown"><button class="category-toggle dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-list"></i>Danh mục</button><ul class="dropdown-menu"><li><a class="dropdown-item" href="buildpc.html">Xây dựng cấu hình PC</a></li><li><a class="dropdown-item" href="products.html?category=components">PC &amp; linh kiện</a></li><li><a class="dropdown-item" href="products.html?category=monitor">Màn hình máy tính</a></li><li><a class="dropdown-item" href="products.html?category=gaming">Gaming gear</a></li></ul></div>
    +				<form class="search-form" role="search" action="products.html" method="get"><label class="visually-hidden" for="site-search">Tìm kiếm sản phẩm</label><input id="site-search" class="form-control" type="search" name="q" placeholder="Nhập sản phẩm cần tìm..." autocomplete="off"><button class="search-button" type="submit" aria-label="Tìm kiếm"><i class="bi bi-search"></i></button></form>
    +				<div class="header-actions"><a class="header-action" href="contact.html"><i class="bi bi-headset"></i><span><small>Hỗ trợ khách hàng</small>Liên hệ</span></a><a class="header-action" href="login.html"><i class="bi bi-person-circle"></i><span><small>Tài khoản</small>Đăng nhập</span></a><a class="header-action cart-action" href="cart.html"><span class="cart-icon"><i class="bi bi-cart3"></i><b>0</b></span><span><small>Giỏ hàng</small>Thanh toán</span></a></div>
    +			</div></div>
    +			<nav class="header-bottom" aria-label="Danh mục nổi bật"><div class="container header-bottom-inner"><a class="all-categories" href="products.html"><i class="bi bi-list"></i>Danh mục sản phẩm</a><div class="header-menu"><a href="buildpc.html">Build PC</a><a href="products.html?category=components">PC Gaming</a><a href="products.html?category=components">PC Đồ họa AI</a><a href="products.html?category=monitor">Màn hình</a><a href="products.html?category=components">Linh kiện</a><a href="products.html?category=gaming">Gaming gear</a><a href="news.html">Khuyến mãi</a></div></div></nav>
    +		</header>`;
    +
    +	function mountHeader() {
    +		const existingHeader = document.querySelector(".store-header, .header, .site-header");
    +		if (!existingHeader || existingHeader.classList.contains("store-header")) return;
    +		existingHeader.outerHTML = headerMarkup;
    +		window.updateCartCount?.();
    +	}
    +
    +	if (document.readyState === "loading") {
    +		document.addEventListener("DOMContentLoaded", mountHeader, { once: true });
    +	} else {
    +		mountHeader();
    +	}
    +})();

## Activity

    file changes: Completed · 1 changes
    Delete: C:\Users\Admin\Documents\Btl-php\js\csv-parser.js
    /**
     * Utility to parse CSV files and convert model names to URL slugs
     */
    
    const FALLBACK_PRODUCTS = [
    	{
    		Hãng: "Intel",
    		Model: "Core i5-12400F",
    		Socket: "LGA1700",
    		"Số nhân/luồng": "6 nhân 12 luồng",
    		"Xung nhịp": "Up to 4.4 GHz",
    		TDP: "65W",
    		iGPU: "Không có",
    		"Giá TB (VNĐ)": "3.200.000 đ",
    		Ảnh: "12400F-1.webp|12400F-2.webp",
    	},
    	{
    		Hãng: "Intel",
    		Model: "Core i7-14700K",
    		Socket: "LGA1700",
    		"Số nhân/luồng": "20 nhân 28 luồng",
    		"Xung nhịp": "Up to 5.6 GHz",
    		TDP: "125W",
    		iGPU: "Intel UHD 770",
    		"Giá TB (VNĐ)": "10.500.000 đ",
    		Ảnh: "14700K-1.webp|14700K-2.webp",
    	},
    	{
    		Hãng: "Intel",
    		Model: "Core Ultra 7 265K",
    		Socket: "LGA1851",
    		"Số nhân/luồng": "20 nhân 20 luồng",
    		"Xung nhịp": "Up to 5.5 GHz",
    		TDP: "125W",
    		iGPU: "Intel Graphics (4 Xe-cores)",
    		"Giá TB (VNĐ)": "10.200.000 đ",
    		Ảnh: "265K-1.jpg|265K-2.jpg",
    	},
    ];
    
    function toSlug(str) {
    	if (!str) return "";
    	return str
    		.toString()
    		.toLowerCase()
    		.trim()
    		.normalize("NFD")
    		.replace(/[\u0300-\u036f]/g, "")
    		.replace(/[đĐ]/g, "d")
    		.replace(/[^a-z0-9 -]/g, "")
    		.replace(/\s+/g, "-")
    		.replace(/-+/g, "-");
    }
    
    async function fetchAndParseCSV(csvPath, fallbackProducts = FALLBACK_PRODUCTS) {
    	try {
    		const response = await fetch(csvPath);
    		if (!response.ok) return fallbackProducts;
    		const text = await response.text();
    		const parsed = parseCSVText(text);
    		return parsed.length > 0 ? parsed : fallbackProducts;
    	} catch (err) {
    		return fallbackProducts;
    	}
    }
    
    function parseCSVText(text) {
    	const lines = text
    		.split(/\r?\n/)
    		.map((l) => l.trim())
    		.filter(Boolean);
    	if (lines.length < 2) return [];
    	const headers = parseCSVLine(lines[0]);
    	return lines.slice(1).map((line) => {
    		const values = parseCSVLine(line);
    		return Object.fromEntries(headers.map((h, i) => [h.trim(), (values[i] || "").trim()]));
    	});
    }
    
    function parseCSVLine(line) {
    	return [...line.matchAll(/("([^"]*)")|([^,]+)|(?<=,)(?=,|$)/g)].map((m) => m[2] ?? m[3] ?? "");
    }

## Activity

    file changes: Completed · 1 changes
    Add: C:\Users\Admin\Documents\Btl-php\js\csv-parser.js
    /**
     * Utility to parse the catalog CSV files and convert product names to URL slugs.
     */
    
    function toSlug(str) {
    	if (!str) return "";
    	return str
    		.toString()
    		.toLowerCase()
    		.trim()
    		.normalize("NFD")
    		.replace(/[\u0300-\u036f]/g, "")
    		.replace(/[đĐ]/g, "d")
    		.replace(/[^a-z0-9 -]/g, "")
    		.replace(/\s+/g, "-")
    		.replace(/-+/g, "-");
    }
    
    async function fetchAndParseCSV(csvPath, fallbackProducts = []) {
    	try {
    		const response = await fetch(csvPath);
    		if (!response.ok) return fallbackProducts;
    		const text = await response.text();
    		const parsed = parseCSVText(text);
    		return parsed.length > 0 ? parsed : fallbackProducts;
    	} catch {
    		return fallbackProducts;
    	}
    }
    
    function parseCSVText(text) {
    	const lines = text
    		.split(/\r?\n/)
    		.map((line) => line.trim())
    		.filter(Boolean);
    	if (lines.length < 2) return [];
    	const headers = parseCSVLine(lines[0]);
    	return lines.slice(1).map((line) => {
    		const values = parseCSVLine(line);
    		return Object.fromEntries(
    			headers.map((header, index) => [header.trim(), (values[index] || "").trim()]),
    		);
    	});
    }
    
    function parseCSVLine(line) {
    	return [...line.matchAll(/("([^"]*)")|([^,]+)|(?<=,)(?=,|$)/g)].map(
    		(match) => match[2] ?? match[3] ?? "",
    	);
    }

## Activity

    file changes: Completed · 1 changes
    Update { move_path: None }: C:\Users\Admin\Documents\Btl-php\css\style.css
    @@ -259,9 +259,176 @@
     
    -.header-action small {
    -	display: block;
    -	color: #68737d;
    -	font-size: 0.7rem;
    -	font-weight: 400;
    -	margin-bottom: 2px;
    -}
    +.header-action small {
    +	display: block;
    +	color: #68737d;
    +	font-size: 0.7rem;
    +	font-weight: 400;
    +	margin-bottom: 2px;
    +}
    +
    +/* Shared TNC-style header: utility bar, search bar, and category navigation. */
    +.store-header {
    +	position: sticky;
    +	top: 0;
    +	z-index: 1030;
    +	width: 100%;
    +	background: #fff;
    +	box-shadow: 0 2px 12px rgba(9, 47, 100, 0.12);
    +}
    +
    +.store-header .header-top {
    +	background: #0a57aa;
    +	color: #fff;
    +	font-size: 0.8rem;
    +}
    +
    +.header-top-inner,
    +.header-mid-inner,
    +.header-bottom-inner {
    +	display: flex;
    +	align-items: center;
    +}
    +
    +.header-top-inner {
    +	min-height: 33px;
    +	justify-content: space-between;
    +}
    +
    +.store-header .header-top a {
    +	display: inline-flex;
    +	align-items: center;
    +	gap: 0.4rem;
    +	color: #fff;
    +	font-weight: 600;
    +	text-decoration: none;
    +}
    +
    +.header-contact {
    +	display: flex;
    +	align-items: center;
    +	gap: 1.25rem;
    +}
    +
    +.store-header .header-mid {
    +	background: #fff;
    +	border-bottom: 1px solid #e7edf5;
    +}
    +
    +.header-mid-inner {
    +	gap: 0.85rem;
    +	min-height: 76px;
    +}
    +
    +.store-header .logo {
    +	flex: 0 0 112px;
    +	margin: 0;
    +}
    +
    +.store-header .logo img {
    +	width: 108px;
    +	max-width: 100%;
    +}
    +
    +.store-header .category-toggle {
    +	display: inline-flex;
    +	align-items: center;
    +	gap: 0.45rem;
    +	min-height: 42px;
    +	border: 1px solid #d7e1ef;
    +	border-radius: 4px;
    +	background: #f6f9fd;
    +	color: #164b8b;
    +}
    +
    +.store-header .search-form {
    +	flex: 1 1 420px;
    +	margin: 0;
    +}
    +
    +.store-header .search-form .form-control {
    +	min-height: 42px;
    +	border-color: #cbd9e9;
    +	border-radius: 4px;
    +	background: #fff;
    +}
    +
    +.store-header .search-button {
    +	top: 3px;
    +	right: 3px;
    +	width: 36px;
    +	height: 36px;
    +	border-radius: 3px;
    +	background: #0a57aa;
    +}
    +
    +.store-header .header-actions {
    +	flex: 0 0 auto;
    +	gap: 1rem;
    +}
    +
    +.store-header .header-action {
    +	gap: 0.45rem;
    +	color: #1c3553;
    +	font-size: 0.78rem;
    +}
    +
    +.store-header .header-action:hover {
    +	color: #0a57aa;
    +}
    +
    +.store-header .header-action > i,
    +.store-header .cart-icon > i {
    +	color: #0a57aa;
    +	font-size: 1.4rem;
    +}
    +
    +.store-header .header-action small {
    +	color: #6b7b8e;
    +	font-size: 0.68rem;
    +}
    +
    +.store-header .cart-icon b {
    +	background: #e52d37;
    +}
    +
    +.store-header .header-bottom {
    +	background: #073b78;
    +}
    +
    +.header-bottom-inner {
    +	min-height: 42px;
    +}
    +
    +.all-categories {
    +	display: inline-flex;
    +	align-items: center;
    +	gap: 0.55rem;
    +	align-self: stretch;
    +	padding: 0 1rem;
    +	background: #0a57aa;
    +	color: #fff;
    +	font-size: 0.84rem;
    +	font-weight: 700;
    +	text-decoration: none;
    +	white-space: nowrap;
    +}
    +
    +.store-header .header-menu {
    +	display: flex;
    +	align-items: center;
    +	min-width: 0;
    +	overflow-x: auto;
    +}
    +
    +.store-header .header-menu a {
    +	padding: 0.7rem 0.8rem;
    +	color: #fff;
    +	font-size: 0.8rem;
    +	font-weight: 600;
    +	text-decoration: none;
    +	white-space: nowrap;
    +}
    +
    +.store-header .header-menu a:hover {
    +	background: rgba(255, 255, 255, 0.12);
    +}
     

## Activity

    file changes: Completed · 1 changes
    Update { move_path: None }: C:\Users\Admin\Documents\Btl-php\css\style.css
    @@ -2045,6 +2045,21 @@
     /* Responsive adjustments */
    -@media (max-width: 991.98px) {
    -	.header-actions {
    -		justify-content: flex-end;
    -		padding-bottom: 0.25rem;
    +@media (max-width: 991.98px) {
    +	.store-header .header-mid-inner {
    +		flex-wrap: wrap;
    +		padding-top: 0.65rem;
    +		padding-bottom: 0.65rem;
    +	}
    +	.store-header .search-form {
    +		order: 3;
    +		flex-basis: calc(100% - 125px);
    +	}
    +	.store-header .header-actions {
    +		margin-left: auto;
    +	}
    +	.store-header .header-bottom {
    +		overflow-x: auto;
    +	}
    +	.header-actions {
    +		justify-content: flex-end;
    +		padding-bottom: 0.25rem;
     	}
    @@ -2064,5 +2079,37 @@
     
    -@media (max-width: 575.98px) {
    -	.header-actions {
    -		justify-content: space-between;
    +@media (max-width: 575.98px) {
    +	.header-contact a:last-child,
    +	.store-header .header-action:not(.cart-action) span {
    +		display: none;
    +	}
    +	.store-header .header-top-inner {
    +		font-size: 0.72rem;
    +	}
    +	.header-contact {
    +		gap: 0.65rem;
    +	}
    +	.store-header .logo {
    +		flex-basis: 90px;
    +	}
    +	.store-header .logo img {
    +		width: 86px;
    +	}
    +	.store-header .category-toggle {
    +		padding: 0.55rem 0.65rem;
    +	}
    +	.store-header .search-form {
    +		flex-basis: 100%;
    +		order: 4;
    +	}
    +	.store-header .header-actions {
    +		gap: 0.7rem;
    +	}
    +	.store-header .header-action {
    +		font-size: 0.7rem;
    +	}
    +	.store-header .header-bottom-inner {
    +		min-width: max-content;
    +	}
    +	.header-actions {
    +		justify-content: space-between;
     		gap: 0.75rem;

## Activity

    file changes: Completed · 15 changes
    Update { move_path: None }: C:\Users\Admin\Documents\Btl-php\404.html
    @@ -16,3 +16,4 @@
     		/>
    -	</head>
    +		<script defer src="js/header.js"></script>
    +	</head>
     	<body>
    Update { move_path: None }: C:\Users\Admin\Documents\Btl-php\about.html
    @@ -16,3 +16,4 @@
     		/>
    -	</head>
    +		<script defer src="js/header.js"></script>
    +	</head>
     	<body>
    Update { move_path: None }: C:\Users\Admin\Documents\Btl-php\buildpc.html
    @@ -20,3 +20,4 @@
     		/>
    -	</head>
    +		<script defer src="js/header.js"></script>
    +	</head>
     	<body class="buildpc-body">
    Update { move_path: None }: C:\Users\Admin\Documents\Btl-php\cart.html
    @@ -16,3 +16,4 @@
     		/>
    -	</head>
    +		<script defer src="js/header.js"></script>
    +	</head>
     	<body>
    Update { move_path: None }: C:\Users\Admin\Documents\Btl-php\checkout.html
    @@ -16,3 +16,4 @@
     		/>
    -	</head>
    +		<script defer src="js/header.js"></script>
    +	</head>
     	<body>
    Update { move_path: None }: C:\Users\Admin\Documents\Btl-php\contact.html
    @@ -16,3 +16,4 @@
     		/>
    -	</head>
    +		<script defer src="js/header.js"></script>
    +	</head>
     	<body>
    Update { move_path: None }: C:\Users\Admin\Documents\Btl-php\forgot-password.html
    @@ -16,3 +16,4 @@
     		/>
    -	</head>
    +		<script defer src="js/header.js"></script>
    +	</head>
     	<body style="background-color: #f5f7fa">
    Update { move_path: None }: C:\Users\Admin\Documents\Btl-php\index.html
    @@ -31,3 +31,4 @@
     		></script>
    -	</head>
    +		<script defer src="js/header.js"></script>
    +	</head>
     	<body>
    Update { move_path: None }: C:\Users\Admin\Documents\Btl-php\login.html
    @@ -16,3 +16,4 @@
     		/>
    -	</head>
    +		<script defer src="js/header.js"></script>
    +	</head>
     	<body>
    Update { move_path: None }: C:\Users\Admin\Documents\Btl-php\news-post.html
    @@ -16,3 +16,4 @@
     		<link rel="stylesheet" href="css/style.css" />
    -	</head>
    +		<script defer src="js/header.js"></script>
    +	</head>
     	<body>
    Update { move_path: None }: C:\Users\Admin\Documents\Btl-php\news.html
    @@ -16,3 +16,4 @@
     		<link rel="stylesheet" href="css/style.css" />
    -	</head>
    +		<script defer src="js/header.js"></script>
    +	</head>
     	<body>
    Update { move_path: None }: C:\Users\Admin\Documents\Btl-php\product-detail.html
    @@ -16,3 +16,4 @@
     		/>
    -	</head>
    +		<script defer src="js/header.js"></script>
    +	</head>
     	<body>
    Update { move_path: None }: C:\Users\Admin\Documents\Btl-php\products.html
    @@ -16,3 +16,4 @@
     		/>
    -	</head>
    +		<script defer src="js/header.js"></script>
    +	</head>
     	<body>
    Update { move_path: None }: C:\Users\Admin\Documents\Btl-php\profile.html
    @@ -15,3 +15,4 @@
     		/>
    -	</head>
    +		<script defer src="js/header.js"></script>
    +	</head>
     	<body>
    Update { move_path: None }: C:\Users\Admin\Documents\Btl-php\register.html
    @@ -16,3 +16,4 @@
     		/>
    -	</head>
    +		<script defer src="js/header.js"></script>
    +	</head>
     	<body>

## Activity

    $ "C:\\Windows\\System32\\WindowsPowerShell\\v1.0\\powershell.exe" -Command '$content = Get-Content -LiteralPath index.html; foreach ($number in 395..418) { $line = $content[$number - 1]; "$number|" + ($line -replace "`t", "[TAB]") }; $bytes = [System.IO.File]::ReadAllBytes((Resolve-Path index.html)); $needle = [System.Text.Encoding]::UTF8.GetBytes('"'featured-products-list'); "'$offset = -1; for ($i = 0; $i -le $bytes.Length - $needle.Length; $i++) { $match = $true; for ($j = 0; $j -lt $needle.Length; $j++) { if ($bytes[$i + $j] -ne $needle[$j]) { $match = $false; break } }; if ($match) { $offset = $i; break } }; "$offset"; [BitConverter]::ToString($bytes[$offset..($offset + 650)])'
    status: Completed · exit 0
      395|[TAB][TAB][TAB][TAB][TAB]</div>
      396|[TAB][TAB][TAB][TAB][TAB]<div class="row g-4" id="featured-products-list">
      397|[TAB][TAB][TAB][TAB][TAB][TAB]<div class="col-sm-6 col-lg-3 d-flex">
      398|[TAB][TAB][TAB][TAB][TAB][TAB][TAB]<article class="product-card d-flex w-100 flex-column">
      399|[TAB][TAB][TAB][TAB][TAB][TAB][TAB][TAB]<div class="product-image">
      400|[TAB][TAB][TAB][TAB][TAB][TAB][TAB][TAB][TAB]<i class="bi bi-cpu"></i>
      401|[TAB][TAB][TAB][TAB][TAB][TAB][TAB][TAB][TAB]<span class="product-tag">BÃ¡n cháº¡y</span>
      402|[TAB][TAB][TAB][TAB][TAB][TAB][TAB][TAB]</div>
      403|[TAB][TAB][TAB][TAB][TAB][TAB][TAB][TAB]<p class="product-brand">Intel Â· Socket LGA1700</p>
      404|[TAB][TAB][TAB][TAB][TAB][TAB][TAB][TAB]<h3>
      405|[TAB][TAB][TAB][TAB][TAB][TAB][TAB][TAB][TAB]<a
      406|[TAB][TAB][TAB][TAB][TAB][TAB][TAB][TAB][TAB][TAB]href="product-detail.html?model=Core%20i5-12400F"
      407|[TAB][TAB][TAB][TAB][TAB][TAB][TAB][TAB][TAB][TAB]class="text-decoration-none text-dark"
      408|[TAB][TAB][TAB][TAB][TAB][TAB][TAB][TAB][TAB]>
      409|[TAB][TAB][TAB][TAB][TAB][TAB][TAB][TAB][TAB][TAB]CPU Intel Core i5-12400F
      410|[TAB][TAB][TAB][TAB][TAB][TAB][TAB][TAB][TAB]</a>
      411|[TAB][TAB][TAB][TAB][TAB][TAB][TAB][TAB]</h3>
      412|[TAB][TAB][TAB][TAB][TAB][TAB][TAB][TAB]<strong class="product-price">3.200.000 Ä‘</strong>
      413|[TAB][TAB][TAB][TAB][TAB][TAB][TAB][TAB]<button class="btn btn-outline-primary w-100 mt-3">
      414|[TAB][TAB][TAB][TAB][TAB][TAB][TAB][TAB][TAB]<i class="bi bi-cart-plus me-2"></i>
      415|[TAB][TAB][TAB][TAB][TAB][TAB][TAB][TAB][TAB]ThÃªm vÃ o giá»
      416|[TAB][TAB][TAB][TAB][TAB][TAB][TAB][TAB]</button>
      417|[TAB][TAB][TAB][TAB][TAB][TAB][TAB]</article>
      418|[TAB][TAB][TAB][TAB][TAB][TAB]</div>
      12858
      66-65-61-74-75-72-65-64-2D-70-72-6F-64-75-63-74-73-2D-6C-69-73-74-22-3E-0D-0A-09-09-09-09-09-09-3C-64-69-76-20-63-6C-61-73-73-3D-22-63-6F-6C-2D-73-6D-2D-36-20-63-6F-6C-2D-6C-67-2D-33-20-64-2D-66-6C-65-78-22-3E-0D-0A-09-09-09-09-09-09-09-3C-61-72-74-69-63-6C-65-20-63-6C-61-73-73-3D-22-70-72-6F-64-75-63-74-2D-63-61-72-64-20-64-2D-66-6C-65-78-20-77-2D-31-30-30-20-66-6C-65-78-2D-63-6F-6C-75-6D-6E-22-3E-0D-0A-09-09-09-09-09-09-09-09-3C-64-69-76-20-63-6C-61-73-73-3D-22-70-72-6F-64-75-63-74-2D-69-6D-61-67-65-22-3E-0D-0A-09-09-09-09-09-09-09-09-09-3C-69-20-63-6C-61-73-73-3D-22-62-69-20-62-69-2D-63-70-75-22-3E-3C-2F-69-3E-0D-0A-09-09-09-09-09-09-09-09-09-3C-73-70-61-6E-20-63-6C-61-73-73-3D-22-70-72-6F-64-75-63-74-2D-74-61-67-22-3E-42-C3-A1-6E-20-63-68-E1-BA-A1-79-3C-2F-73-70-61-6E-3E-0D-0A-09-09-09-09-09-09-09-09-3C-2F-64-69-76-3E-0D-0A-09-09-09-09-09-09-09-09-3C-70-20-63-6C-61-73-73-3D-22-70-72-6F-64-75-63-74-2D-62-72-61-6E-64-22-3E-49-6E-74-65-6C-20-C2-B7-20-53-6F-63-6B-65-74-20-4C-47-41-31-37-30-30-3C-2F-70-3E-0D-0A-09-09-09-09-09-09-09-09-3C-68-33-3E-0D-0A-09-09-09-09-09-09-09-09-09-3C-61-0D-0A-09-09-09-09-09-09-09-09-09-09-68-72-65-66-3D-22-70-72-6F-64-75-63-74-2D-64-65-74-61-69-6C-2E-68-74-6D-6C-3F-6D-6F-64-65-6C-3D-43-6F-72-65-25-32-30-69-35-2D-31-32-34-30-30-46-22-0D-0A-09-09-09-09-09-09-09-09-09-09-63-6C-61-73-73-3D-22-74-65-78-74-2D-64-65-63-6F-72-61-74-69-6F-6E-2D-6E-6F-6E-65-20-74-65-78-74-2D-64-61-72-6B-22-0D-0A-09-09-09-09-09-09-09-09-09-3E-0D-0A-09-09-09-09-09-09-09-09-09-09-43-50-55-20-49-6E-74-65-6C-20-43-6F-72-65-20-69-35-2D-31-32-34-30-30-46-0D-0A-09-09-09-09-09-09-09-09-09-3C-2F-61-3E-0D-0A-09-09-09-09-09-09-09-09-3C-2F-68-33-3E-0D-0A-09-09-09-09-09-09-09-09-3C-73-74-72-6F-6E-67-20-63-6C-61-73-73-3D-22-70-72-6F-64-75-63-74-2D-70-72-69-63-65-22-3E-33-2E-32-30-30-2E-30-30-30-20-C4-91-3C-2F-73-74-72-6F-6E-67-3E-0D-0A-09-09-09-09-09-09-09-09-3C-62-75-74-74-6F-6E-20-63-6C-61-73-73-3D-22-62-74-6E-20-62-74-6E-2D-6F

## Activity

    file changes: Completed · 1 changes
    Update { move_path: None }: C:\Users\Admin\Documents\Btl-php\index.html
    @@ -395,26 +395,8 @@
     					</div>
    -					<div class="row g-4" id="featured-products-list">
    -						<div class="col-sm-6 col-lg-3 d-flex">
    -							<article class="product-card d-flex w-100 flex-column">
    -								<div class="product-image">
    -									<i class="bi bi-cpu"></i>
    -									<span class="product-tag">Bán chạy</span>
    -								</div>
    -								<p class="product-brand">Intel · Socket LGA1700</p>
    -								<h3>
    -									<a
    -										href="product-detail.html?model=Core%20i5-12400F"
    -										class="text-decoration-none text-dark"
    -									>
    -										CPU Intel Core i5-12400F
    -									</a>
    -								</h3>
    -								<strong class="product-price">3.200.000 đ</strong>
    -								<button class="btn btn-outline-primary w-100 mt-3">
    -									<i class="bi bi-cart-plus me-2"></i>
    -									Thêm vào giỏ
    -								</button>
    -							</article>
    -						</div>
    -					</div>
    +					<div class="row g-4" id="featured-products-list">
    +						<div class="col-12 py-5 text-center text-muted">
    +							<div class="spinner-border spinner-border-sm me-2" role="status"></div>
    +							Đang tải sản phẩm...
    +						</div>
    +					</div>
     				</div>
