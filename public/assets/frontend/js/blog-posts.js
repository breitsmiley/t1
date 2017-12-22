$(function () {

    var postList = $("#postList");

    var postManager = {
        currentPage: 1,

        fetchAndRenderPage: function (page) {

            page = page || 1;

            $.ajax({
                type: 'POST',
                url: '/ajax/blog/posts/show',
                data: {
                    page: page
                },
                beforeSend: function() {
                },
                complete: function () {
                }
            }).done(function (data) {
                postList.html(data.html);
                postManager.currentPage = data.page;
            }).fail(function (jqXHR, textStatus, errorThrown) {
                alert(errorThrown);
            });
        },
        init: function() {
            this.fetchAndRenderPage();
        }
    };

    postList.on("click", "#postPrevBtn,#postNextBtn", function (e) {

        e.preventDefault();
        e.stopPropagation();

        var newPage = 1;
        if (e.target.id === 'postPrevBtn') {
            newPage = postManager.currentPage - 1;
        } else if (e.target.id === 'postNextBtn'){
            newPage = postManager.currentPage + 1;
        } else {
            console.log('Strange button ID #' + e.target.id);
        }

        postManager.fetchAndRenderPage(newPage);
    });

    postManager.init();
});

