/**
 * Created with JetBrains PhpStorm.
 * User: DezMonT
 * Date: 09.10.14
 * Time: 11:54
 * To change this template use File | Settings | File Templates.
 */
function initSelect(elem,listUrl,idsUrl,isMultiple,width){
    var width = width || '50%';
    var options = {
        containerCssClass: 'tpx-select2-container',
        dropdownCssClass: 'tpx-select2-drop',
        width: width,
        multiple : isMultiple,
        ajax : {
            url: listUrl,
            dataType: 'json',
            data: function(term)
            {
                return {
                    value :term
                };
            },
            results: function(data)
            {
                return {results: data.results};
            }

        },
        initSelection: function(element, callback) {
            var id=$(element).attr('value');
            if (id) {
                $.ajax({
                    url: idsUrl,
                    dataType: 'json',
                    data :
                    {
                        id : id
                    }
                }).done(function(data) { callback(data.results)});
            }
        }

    };

    return elem.select2(options);
}