var fx_correct = new Howl({src: ['https://abdulbaha.fra1.cdn.digitaloceanspaces.com/audio/fx_correct.mp3']});
var fx_incorrect = new Howl({src: ['https://abdulbaha.fra1.cdn.digitaloceanspaces.com/audio/fx_incorrect.mp3']});

window.addEventListener('scroll', function () {
    const navBar = document.querySelector('.nav-sticky');

    if (window.scrollY >= 80) {
        navBar.style.transform = 'none';
    } else {
        navBar.style.transform = 'translateY(-6.4rem)';
    }
});

function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function () {
        alert('Copied to clipboard.');
    }, function (err) {
        alert('Could not copy text: ', err);
    });
}

function generateArabicName() {
    const names = [
        'محمد',
        'أحمد',
        'علي',
        'حسن',
        'حسين',
        'عبد',
        'عمر',
        'إبراهيم',
        'يوسف',
        'خالد',
        'طارق',
        'حمزة',
        'مصطفى',
        'محمود',
        'كريم',
        'أسامة',
        'عادل',
        'عمرو',
        'ناصر',
        'ياسين',
        'هشام',
        'سمير',
        'وليد',
        'موسى',
        'عماد',
        'يحيى',
        'أنس',
        'فيصل',
        'ماجد',
        'إسماعيل',
        'راشد',
        'زياد',
        'فارس',
        'جابر',
        'بلال',
        'زكريا',
        'سامر',
        'نبيل',
        'أمين',
        'طلال',
        'سعيد',
        'سليم',
        'جمال',
        'بسام',
        'فادي',
        'مروان',
        'فارس',
        'أيمن',
        'مازن',
        'رامي',
        'عزيز',
        'سليمان',
        'علاء',
        'إيهاب',
        'عماد',
        'رائد',
        'نجيب',
        'إسماعيل',
        'هاني',
        'وائل',
        'عدنان',
        'ممدوح',
        'منير',
        'نادر',
        'مراد',
        'عبد الرحمن',
        'قاسم',
        'غازي',
        'داود',
        'عاصم',
        'هاشم',
        'شادي',
        'عمار',
        'رياض',
        'فهد',
        'مختار',
        'صبري',
        'بدري',
        'فؤاد',
        'فاروق',
        'عصام',
        'زهير',
        'نواف',
        'ثامر',
        'حكيم',
        'صافي',
        'معين',
        'نزيه',
        'سهيل',
        'إياد',
        'سنان',
        'رفيق',
        'بهاء',
        'رفيق',
    ];

    const randomIndex = Math.floor(Math.random() * names.length);
    return names[randomIndex];
}

var nameGenerator = document.getElementById('name-generator');
if (nameGenerator) {
    nameGenerator.addEventListener('click', () => {
        const arabicName = generateArabicName();
        document.getElementById('ar_name').value = arabicName;
    });
}

/* BUTTONS */

$('.nav-user-toggle').click(function () {
    $('.nav-user').toggleClass('show');

    $('.nav-mobile').removeClass('show');
});

$('.nav-mobile-toggle').click(function () {
    $('.nav-mobile').toggleClass('show');

    $('.nav-user').removeClass('show');
});

/* ACTIVITES */

$('body').on('click', '.activity-select button', function () {
    if ($(this).hasClass('selected')) {
        $(this).removeClass('selected');
    } else {
        $(this).closest('.activity-select').find('button').removeClass('selected');
        $(this).toggleClass('selected');
    }
})
