/**
 * Created by michaelzapf on 11.06.17.
 */

function PresentationLoader() {
    this.currentUrl = '';
    this.currentLastModified = 0;
    this.recheckTimeout = 60000;

    this.check = function() {
        $.ajax(window.currentUrl, {
            method: 'get',
            headers: getAjaxHeaders(),
            dataType: 'jsonp',
            success: $.proxy(function(data) {
                if (data.lastModified == undefined || data.url == undefined) {
                    return;
                }

                if (this.currentUrl == data.url && this.currentLastModified == data.lastModified) {
                    return;
                }

                this.currentUrl = data.url;
                this.currentLastModified = data.lastModified;
                this.load(this.currentUrl);
            }, this)
        });
        setTimeout($.proxy(this.check, this), this.recheckTimeout);
    };

    this.load = function(url) {
        $.ajax(url, {
            method: 'get',
            headers: getAjaxHeaders(),
            dataType: 'jsonp',
            success: $.proxy(function(data) {
                this.clear();

                var pres = new Presentation();
                keyboardHandler.presentation = pres;
                pres.container = $('#canvas');
                pres.load(data);
                //TODO: Warten bis Bilder geladen sind?
                // http://stackoverflow.com/a/5623965
            }, this)
        });
    };

    this.clear = function () {
        $('#canvas').empty();
    }
}

function getAjaxHeaders() {
    var headers = {};
    if (window.previewMode > 0) {
        headers['X-PREVIEW'] = '1';
    }
    if (window.screenGuid.length > 0) {
        headers['X-SCREEN'] = window.screenGuid;
    }
    return headers;
}
