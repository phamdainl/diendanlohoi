jQuery('document').ready(function ($) {

    const selectedCids = JSON.parse($('#selectedCids').text());
    const $filterCategories = $('#filter_categories');
    $filterCategories.val(selectedCids);

    /**
     * Apologies for the variables but the element ids should be descriptive enough
     * The GET parameters are made small so that in future they don't hit the max
     * query limits if set in any servers (usually 1024)
     */
    $('#search_form').on('submit', function (e) {
        e.preventDefault();
        search();
    });

    $('#sort_on, #order_by').on('change', search);

    $('#deselect_all_categories').on('click', function () {
        $filterCategories.val([]);
    });

    function search() {
        const q = $('#search_what').val();
        const w = $('#search_within').val();
        const m = $('#match_titles').val();
        const ob = $('#order_by').val();
        const so = $('#sort_on').val();
        const p = $('#currPage').text();

        const cids = $filterCategories.val();
        let ids = "";
        if (cids != null) {
            ids = "&cids=" + cids.join(",");
        }

        window.location = `${codo_defs.url}search/${p}&q=${q}&w=${w}&m=${m}&ob=${ob}&so=${so}${ids}`;
    }

});