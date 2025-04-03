var fx_correct = new Howl({src: ['https://abdulbaha.fra1.cdn.digitaloceanspaces.com/audio/fx_correct.mp3']});
var fx_incorrect = new Howl({src: ['https://abdulbaha.fra1.cdn.digitaloceanspaces.com/audio/fx_incorrect.mp3']});

function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function () {
        alert('Copied to clipboard.');
    }, function (err) {
        alert('Could not copy text: ', err);
    });
}

/* ACTIVITES */

$('body').on('click', '.activity-select button', function () {
    if ($(this).hasClass('selected')) {
        $(this).removeClass('selected');
    } else {
        $(this).closest('.activity-select').find('button').removeClass('selected');
        $(this).toggleClass('selected');
    }
})
