$(document).ready(function () {
    $('.social-way div').on('click', function () {
        var social = $(this).data('social');
        var href = $(this).data('href');
        $.ajax({
            type: "POST",
            url: '/auth/social',
            data: {"social": social,
                "href": href},
            success: function (data) {
                console.log(data);
            }
        })
    });
});