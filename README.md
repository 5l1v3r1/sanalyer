
## sanalyer.com

### Github Komutları

#### Branch Başlatma

Başlatmadan önce Develop branchında ``git pull origin develop`` kodunu çalıştırdığınıza emin olun
Hangi branchta olduğunuzu ``git branch`` ile öğrenebilirsiniz. Branch'ta commit yapıp yapmamanız gerektiğini
``git status`` yazarak öğrenebilirsiniz. Eğer clean sonucu alırsanız son bir kez daha ``git pull origin develop`` 
kodunu çalıştırın. Eğer develop branch'ın da değilseniz ``git checkout develop`` ile develop branch'ına geçiş 
yapabilirsiniz.

**Branch başlatma kodları:**

* git checkout develop
* git pull origin develop
* git flow feature start ``<BranchIsmi>``

#### Branch Yükleme Komutları

Branch yüklemeden önce hangi branchta olduğunuzu kontrol edin. ``git branch`` komutu ile hangi branchta olduğunuzu
kontrol edebilirsiniz. Eğer başlattığınız bir ``feature/<BranchIsmi>`` branchında iseniz aşağıda ki kodları girerek 
branchınızı yükleyebilirsiniz. Eğer develop branchında iseniz ``git status`` yazarak göndermeniz gereken dosya varsa
yeni bir branch başlatıp ( ``git flow feature start <BranchIsmi>`` ) aşağıda ki komutları girebilirsiniz.

**Branch Yükleme Komutları:**

* git add .
* git commit -m " ``Yaptığınız değişikliklerin açıklaması`` "
* git flow feature publish

Branch yükleme yaptıktan sonra herkes birleştirme yapacaksa develop branchına geçip indirin. Birleşme sonrası yeni 
branch başlatabilirsiniz. Birleşme yapılmayacağı takdirde develop branchına geçmeden kendi branchınızdan devam ediniz.

#### Opsiyonel Komutlar

**Local branch silme:** 
`` git branch -D <BranchIsmi>``

**Değişiklikleri iptal etme** 
`` git reset HEAD --soft``

### PHP & Composer Komutları

#### Proje Komutları

**Composer Güncelleme:** 
``composer update``

**Composer Autoload Oluşturma:**
``composer dump-autoload``

**Sunucu Başlatma:**
``php artisan serve --port=80``

#### Cache Komutları

**Rota Cachelerinin Temizlenmesi:**
``php artisan route:clear``

**Redis Temizlenmesi:**
``php artisan cache:clear``

**Viewların temizlenmesi:**
``php artisan view:clear``

**Config cache temizlenmesi:**
``php artisan config:clear``
