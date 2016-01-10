(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
'use strict';

Vue.filter('humanReadableFilesize', function (size) {
    if (size) {
        return filesize(size, { round: 0 });
    }
    return '';
});

Vue.filter('semanticFileTypeClass', function (mime) {
    if (semanticFileTypeClassMap[mime]) {
        return semanticFileTypeClassMap[mime];
    }
    return "file outline";
});

function initializeComponents() {

    $('.ui.dropdown').dropdown();
    $('.ui.sortable.table').tablesort();

    $('.ui.sortable.table th.filename').data('sortBy', function (th, td, tablesort) {
        var tag = $(td).data('tag');

        if (tag == 'folder') {
            return '0' + $(td).data('sort-value').toLowerCase();
        }
        return '1' + $(td).data('sort-value').toLowerCase();
    });
}

},{}]},{},[1]);

//# sourceMappingURL=app.js.map
