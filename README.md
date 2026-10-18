# Amber Marketplace

Website mua bán đồ đã qua sử dụng, xây dựng bằng **PHP 8.2+ / Laravel 12**. Người dùng có thể đăng tin, tìm sản phẩm, trao đổi với người bán, đặt mua và theo dõi đơn hàng; quản trị viên xử lý tin đăng và báo cáo vi phạm.

[Cài đặt](#chạy-cục-bộ) · [Cấu hình](#cấu-hình-tích-hợp) · [Kiểm thử](#kiểm-thử) · [Mã nguồn](https://github.com/cong0905/marketPlaces)

## Chức năng trong mã nguồn

| Nhóm | Phạm vi |
| --- | --- |
| Tài khoản | Đăng ký, đăng nhập, xác minh email, hồ sơ; tích hợp Google OAuth |
| Tin đăng | Danh sách/chi tiết sản phẩm, đăng–sửa–xóa tin, tải ảnh, yêu thích |
| Giao dịch | Đặt mua, xác nhận, giao hàng, hoàn thành, hủy đơn; tích hợp VNPay return/IPN |
| Tương tác | Chat theo sản phẩm, thông báo và đánh giá đơn hàng |
| Quản trị | Dashboard, danh mục, duyệt/từ chối tin, xử lý báo cáo, khóa/mở khóa người dùng |

Một số thao tác yêu cầu đăng nhập và xác minh email. Các tích hợp thanh toán, email, OAuth và realtime cần thông tin dịch vụ hợp lệ để hoạt động đầy đủ.

## Công nghệ & cấu trúc

**Backend:** Laravel 12, Eloquent, service/repository. **Giao diện:** Blade, Tailwind CSS, Alpine.js, Vite. **Dữ liệu:** SQLite mặc định local; có cấu hình MySQL. **Tích hợp:** Cloudinary, Reverb/Echo, Google Socialite, VNPay. **Kiểm thử:** PHPUnit. Có Dockerfile và cấu hình Render.

```text
app/Http/       Controller, request và middleware
app/Models/     Model dữ liệu
app/Services/   Xử lý nghiệp vụ và tích hợp
app/Repositories/ Truy cập dữ liệu
database/       Migration, factory và seeder
resources/      Blade, CSS và JavaScript
routes/         Route web, auth và broadcasting
tests/          Kiểm thử tự động
```

## Chạy cục bộ

Chuẩn bị Git, PHP 8.2+ với extension database phù hợp, Composer và Node.js/npm tương thích Vite 7 (ví dụ Node 22.12+).

```powershell
git clone https://github.com/cong0905/marketPlaces.git
cd marketPlaces
composer install
npm ci
Copy-Item .env.example .env
php artisan key:generate
New-Item -ItemType File -Path database/database.sqlite
php artisan migrate
php artisan storage:link
npm run build
php artisan serve
```

Mở `http://127.0.0.1:8000`. Đây là hướng dẫn PowerShell cho database local mới; giữ nguyên database và `.env` nếu đã có dữ liệu. Khi dùng MySQL, cấu hình `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` trước khi migrate và bỏ bước tạo file SQLite.

Để phát triển giao diện, chạy `npm run dev` ở terminal khác. Có thể dùng `composer run dev` để chạy server, queue listener, log viewer và Vite theo script của repository.

## Cấu hình tích hợp

| Nhóm biến trong `.env` | Khi cần |
| --- | --- |
| `APP_URL` | URL ứng dụng và callback đúng môi trường |
| `MAIL_*` | Gửi email thật; mặc định `MAIL_MAILER=log`, xem thư trong log local |
| `CLOUDINARY_URL` | Upload ảnh lên Cloudinary; code có nhánh lưu local khi upload thất bại |
| `GOOGLE_CLIENT_ID`, `GOOGLE_CLIENT_SECRET`, `GOOGLE_REDIRECT_URI` | Đăng nhập Google; callback phải khớp cấu hình Google |
| `VNPAY_TMN_CODE`, `VNPAY_HASH_SECRET`, `VNPAY_URL` | Thanh toán VNPay; xem `config/services.php` và `app/Services/VNPayService.php` |
| `BROADCAST_CONNECTION`, `REVERB_*`, biến frontend tương ứng | Realtime; mặc định broadcasting local là `log` |
| `QUEUE_CONNECTION` | Queue; mặc định dùng database, chạy worker cho tác vụ được đưa vào queue |

Khi bật Reverb, chạy `php artisan reverb:start` ở terminal riêng và build lại frontend sau khi đổi biến frontend. Không đưa `.env` hoặc khóa dịch vụ vào Git.

## Kiểm thử

```powershell
composer test
```

Bộ test có các nhóm auth, profile, product, checkout và review. Việc có test trong repo không khẳng định mọi tích hợp dịch vụ đã được kiểm chứng; dùng môi trường sandbox riêng để kiểm tra thanh toán/callback và email.

## Điểm bắt đầu đọc code

- [Route và luồng truy cập](routes/web.php)
- [Upload và xử lý sản phẩm](app/Services/ProductService.php)
- [Cấu hình tích hợp](config/services.php)
- [Cấu hình mẫu](.env.example)

**Tác giả:** [Nguyễn Đức Công](https://github.com/cong0905)
