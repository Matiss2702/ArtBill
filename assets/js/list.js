$(document).ready(function () {
    const url = new URL(window.location.href);

    const page = parseInt(getParam('page'));
    const search = getParam('search');
    const resultsPerPage = getParam('resultsPerPage');

    if (!page) {
        setParam('page', 1);
    } else if (page === 1) {
        $('#prevPage').addClass('bg-gray-100').removeClass('cursor-pointer');
    }

    if (search || search === '') {
        $('input[name="search"]').val(search);
    } else {
        setParam('search', '');
    }

    if (resultsPerPage) {
        $('select[name="resultsPerPage"]').val(resultsPerPage);
    } else {
        setParam('resultsPerPage', 10);
    }

    $('#prevPage').click(function () {
        if (page > 1) {
            setParam('page', page - 1);
        }
    });

    $('#nextPage').click(function () {
        setParam('page', page + 1);
    });

    $('input[name="search"]').keyup(function (e) {
        const search = $(this);
        if (search.val().length > 30) {
            search.val(search.val().substr(0, 30));
        }
    });

    $('input[name="search"]').keypress(function (e) {
        if (e.which === 13) {
            if ($(this).val() === '') {
                removeParam('search');
            } else {
                setParam('search', encodeURIComponent($(this).val()));
            }
        }
    });

    $('select[name="resultsPerPage"]').change(function () {
        setParam('resultsPerPage', $(this).val());
    });

    function reload() {
        window.location.href = url.href;
    }

    function getParam(param) {
        return url.searchParams.get(param);
    }

    function setParam(param, value) {
        url.searchParams.set(param, value);
        reload();
    }

    function removeParam(param) {
        url.searchParams.delete(param);
        reload();
    }
});
