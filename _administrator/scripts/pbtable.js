! function(t) {
    function e() {
        t("#btn-View").attr("disabled", "disabled"), t("#btn-Edit").attr("disabled", "disabled"), t("#btn-Delete").attr("disabled", "disabled")
    }

    function a() {
        t("#btn-View").removeAttr("disabled"), t("#btn-Edit").removeAttr("disabled"), t("#btn-Delete").removeAttr("disabled")
    }

    function o(t) {
        for (var e = "ÃÀÁÄÂÈÉËÊÌÍÏÎÒÓÖÔÙÚÜÛãàáäâèéëêìíïîòóöôùúüûÑñÇç", a = "AAAAAEEEEIIIIOOOOUUUUaaaaaeeeeiiiioooouuuunncc", o = 0; o < e.length; o++) t = t.replace(e.charAt(o), a.charAt(o));
        return t
    }

    function r(e, a) {
        t("#" + e.attr("id") + '-pbToolbar div[name="sectionForButtons"]').append(a)
    }

    function n() {
        var t = !1;
        if (sorttable = {
                init: function() {
                    arguments.callee.done || (arguments.callee.done = !0, e && clearInterval(e), document.createElement && document.getElementsByTagName && (sorttable.DATE_RE = /^(\d\d?)[\/\.-](\d\d?)[\/\.-]((\d\d)?\d\d)$/, c(document.getElementsByTagName("table"), function(t) {
                        -1 != t.className.search(/\bsortable\b/) && sorttable.makeSortable(t)
                    })))
                },
                makeSortable: function(e) {
                    if (0 == e.getElementsByTagName("thead").length && (the = document.createElement("thead"), the.appendChild(e.rows[0]), e.insertBefore(the, e.firstChild)), null == e.tHead && (e.tHead = e.getElementsByTagName("thead")[0]), 1 == e.tHead.rows.length) {
                        sortbottomrows = [];
                        for (var a = 0; a < e.rows.length; a++) - 1 != e.rows[a].className.search(/\bsortbottom\b/) && (sortbottomrows[sortbottomrows.length] = e.rows[a]);
                        if (sortbottomrows) {
                            null == e.tFoot && (tfo = document.createElement("tfoot"), e.appendChild(tfo));
                            for (var a = 0; a < sortbottomrows.length; a++) tfo.appendChild(sortbottomrows[a]);
                            delete sortbottomrows
                        }
                        headrow = e.tHead.rows[0].cells;
                        for (var a = 0; a < headrow.length; a++) headrow[a].className.match(/\bsorttable_nosort\b/) || (mtch = headrow[a].className.match(/\bsorttable_([a-z0-9]+)\b/), mtch && (override = mtch[1]), headrow[a].sorttable_sortfunction = mtch && "function" == typeof sorttable["sort_" + override] ? sorttable["sort_" + override] : sorttable.guessType(e, a), headrow[a].sorttable_columnindex = a, headrow[a].sorttable_tbody = e.tBodies[0], s(headrow[a], "click", sorttable.innerSortFunction = function() {
                            if (-1 != this.className.search(/\bsorttable_sorted\b/)) return sorttable.reverse(this.sorttable_tbody), this.className = this.className.replace("sorttable_sorted", "sorttable_sorted_reverse"), this.removeChild(document.getElementById("sorttable_sortfwdind")), sortrevind = document.createElement("span"), sortrevind.id = "sorttable_sortrevind", sortrevind.innerHTML = t ? '&nbsp<font face="webdings">5</font>' : "&nbsp;&#x25B4;", void this.appendChild(sortrevind);
                            if (-1 != this.className.search(/\bsorttable_sorted_reverse\b/)) return sorttable.reverse(this.sorttable_tbody), this.className = this.className.replace("sorttable_sorted_reverse", "sorttable_sorted"), this.removeChild(document.getElementById("sorttable_sortrevind")), sortfwdind = document.createElement("span"), sortfwdind.id = "sorttable_sortfwdind", sortfwdind.innerHTML = t ? '&nbsp<font face="webdings">6</font>' : "&nbsp;&#x25BE;", void this.appendChild(sortfwdind);
                            theadrow = this.parentNode, c(theadrow.childNodes, function(t) {
                                1 == t.nodeType && (t.className = t.className.replace("sorttable_sorted_reverse", ""), t.className = t.className.replace("sorttable_sorted", ""))
                            }), sortfwdind = document.getElementById("sorttable_sortfwdind"), sortfwdind && sortfwdind.parentNode.removeChild(sortfwdind), sortrevind = document.getElementById("sorttable_sortrevind"), sortrevind && sortrevind.parentNode.removeChild(sortrevind), this.className += " sorttable_sorted", sortfwdind = document.createElement("span"), sortfwdind.id = "sorttable_sortfwdind", sortfwdind.innerHTML = t ? '&nbsp<font face="webdings">6</font>' : "&nbsp;&#x25BE;", this.appendChild(sortfwdind), row_array = [], col = this.sorttable_columnindex, rows = this.sorttable_tbody.rows;
                            for (var e = 0; e < rows.length; e++) row_array[row_array.length] = [sorttable.getInnerText(rows[e].cells[col]), rows[e]];
                            row_array.sort(this.sorttable_sortfunction), tb = this.sorttable_tbody;
                            for (var e = 0; e < row_array.length; e++) tb.appendChild(row_array[e][1]);
                            delete row_array
                        }))
                    }
                },
                guessType: function(t, e) {
                    sortfn = sorttable.sort_alpha;
                    for (var a = 0; a < t.tBodies[0].rows.length; a++)
                        if (text = sorttable.getInnerText(t.tBodies[0].rows[a].cells[e]), "" != text) {
                            if (text.match(/^-?[£$¤]?[\d,.]+%?$/)) return sorttable.sort_numeric;
                            if (possdate = text.match(sorttable.DATE_RE)) {
                                if (first = parseInt(possdate[1]), second = parseInt(possdate[2]), first > 12) return sorttable.sort_ddmm;
                                if (second > 12) return sorttable.sort_mmdd;
                                sortfn = sorttable.sort_ddmm
                            }
                        }
                    return sortfn
                },
                getInnerText: function(t) {
                    if (!t) return "";
                    if (hasInputs = "function" == typeof t.getElementsByTagName && t.getElementsByTagName("input").length, null != t.getAttribute("sorttable_customkey")) return t.getAttribute("sorttable_customkey");
                    if ("undefined" != typeof t.textContent && !hasInputs) return t.textContent.replace(/^\s+|\s+$/g, "");
                    if ("undefined" != typeof t.innerText && !hasInputs) return t.innerText.replace(/^\s+|\s+$/g, "");
                    if ("undefined" != typeof t.text && !hasInputs) return t.text.replace(/^\s+|\s+$/g, "");
                    switch (t.nodeType) {
                        case 3:
                            if ("input" == t.nodeName.toLowerCase()) return t.value.replace(/^\s+|\s+$/g, "");
                        case 4:
                            return t.nodeValue.replace(/^\s+|\s+$/g, "");
                        case 1:
                        case 11:
                            for (var e = "", a = 0; a < t.childNodes.length; a++) e += sorttable.getInnerText(t.childNodes[a]);
                            return e.replace(/^\s+|\s+$/g, "");
                        default:
                            return ""
                    }
                },
                reverse: function(t) {
                    newrows = [];
                    for (var e = 0; e < t.rows.length; e++) newrows[newrows.length] = t.rows[e];
                    for (var e = newrows.length - 1; e >= 0; e--) t.appendChild(newrows[e]);
                    delete newrows
                },
                sort_numeric: function(t, e) {
                    return aa = parseFloat(t[0].replace(/[^0-9.-]/g, "")), isNaN(aa) && (aa = 0), bb = parseFloat(e[0].replace(/[^0-9.-]/g, "")), isNaN(bb) && (bb = 0), aa - bb
                },
                sort_alpha: function(t, e) {
                    return t[0] == e[0] ? 0 : t[0] < e[0] ? -1 : 1
                },
                sort_ddmm: function(t, e) {
                    return mtch = t[0].match(sorttable.DATE_RE), y = mtch[3], m = mtch[2], d = mtch[1], 1 == m.length && (m = "0" + m), 1 == d.length && (d = "0" + d), dt1 = y + m + d, mtch = e[0].match(sorttable.DATE_RE), y = mtch[3], m = mtch[2], d = mtch[1], 1 == m.length && (m = "0" + m), 1 == d.length && (d = "0" + d), dt2 = y + m + d, dt1 == dt2 ? 0 : dt2 > dt1 ? -1 : 1
                },
                sort_mmdd: function(t, e) {
                    return mtch = t[0].match(sorttable.DATE_RE), y = mtch[3], d = mtch[2], m = mtch[1], 1 == m.length && (m = "0" + m), 1 == d.length && (d = "0" + d), dt1 = y + m + d, mtch = e[0].match(sorttable.DATE_RE), y = mtch[3], d = mtch[2], m = mtch[1], 1 == m.length && (m = "0" + m), 1 == d.length && (d = "0" + d), dt2 = y + m + d, dt1 == dt2 ? 0 : dt2 > dt1 ? -1 : 1
                },
                shaker_sort: function(t, e) {
                    for (var a = 0, o = t.length - 1, r = !0; r;) {
                        r = !1;
                        for (var n = a; o > n; ++n)
                            if (e(t[n], t[n + 1]) > 0) {
                                var s = t[n];
                                t[n] = t[n + 1], t[n + 1] = s, r = !0
                            }
                        if (o--, !r) break;
                        for (var n = o; n > a; --n)
                            if (e(t[n], t[n - 1]) < 0) {
                                var s = t[n];
                                t[n] = t[n - 1], t[n - 1] = s, r = !0
                            }
                        a++
                    }
                }
            }, document.addEventListener && document.addEventListener("DOMContentLoaded", sorttable.init, !1), /WebKit/i.test(navigator.userAgent)) var e = setInterval(function() {
            /loaded|complete/.test(document.readyState) && sorttable.init()
        }, 10);
        window.onload = sorttable.init
    }

    function s(t, e, a) {
        if (t.addEventListener) t.addEventListener(e, a, !1);
        else {
            a.$$guid || (a.$$guid = s.guid++), t.events || (t.events = {});
            var o = t.events[e];
            o || (o = t.events[e] = {}, t["on" + e] && (o[0] = t["on" + e])), o[a.$$guid] = a, t["on" + e] = i
        }
    }

    function i(t) {
        var e = !0;
        t = t || l(((this.ownerDocument || this.document || this).parentWindow || window).event);
        var a = this.events[t.type];
        for (var o in a) this.$$handleEvent = a[o], this.$$handleEvent(t) === !1 && (e = !1);
        return e
    }

    function l(t) {
        return t.preventDefault = l.preventDefault, t.stopPropagation = l.stopPropagation, t
    }
    t.fn.pbTable = function(o) {
        function s() {
            f.onView.call(this)
        }

        function i() {
            f.onEdit.call(this)
        }

        function l() {
            confirm("Are you sure you want to delete this item?") && (m.find("tbody tr." + f.toolbar.selectedClass).remove(), e()), f.onDelete.call(this)
        }

        function d() {
            f.onNew.call(this)
        }

        function c() {
            f.onPrint.call(this)
        }

        function b() {
            f.onServerFind.call(this)
        }

        function h() {
            f.onReceipt.call(this)
        }

        function u(e) {
            t("#" + m.attr("id") + " tbody tr").hide(), t("#" + m.attr("id") + ' tbody tr[data-page="' + e + '"]').show()
        }

        function p(o, r) {
            void 0 != r && o.val(r);
            var n = o.attr("search-in"),
                s = t("#" + n + " td").html().replace("<mark>", "");
            switch (t("#" + n + " td").each(function() {
                s = t(this).html().replace("<mark>", ""), t(this).html(s), s = t(this).html().replace("</mark>", ""), t(this).html(s)
            }), "" === o.val() ? (t("#" + n + " tbody>tr").show(), f.pagination.enabled && u(1)) : (t("#" + n + " tbody>tr").hide(), t("#" + n + " td:contains-ci('" + o.val() + "')").parent("tr").show()), t("#" + n + " tbody>tr:visible").length) {
                case 0:
                    t("#" + n + ' tbody tr[name="msgNoData"]').show(), e();
                    break;
                case 1:
                    t("#" + n + " tbody tr").addClass(f.toolbar.selectedClass), a();
                    break;
                default:
                    t("#" + n + ' tbody tr[name="msgNoData"]').hide(), t("#" + n + " tbody tr").removeClass(f.toolbar.selectedClass), e()
            }
        }
        var f = jQuery.extend(!0, t.fn.pbTable.defaults, o),
            m = t(this),
            g = '<input id="search-' + m.attr("id") + '" search-in="' + m.attr("id") + '" type="search" class="form-control" placeholder="' + f.locale.placeholderSearchBox + '" autofocus>',
            v = '<button title="' + f.locale.btnView + '" id="btn-View" class="btn btn-success" type="button" disabled><i class="glyphicon glyphicon-eye-open"></i></button>',
            w = '<button title="' + f.locale.btnEdit + '" id="btn-Edit" class="btn btn-default btn-warning" type="button" disabled><i class="glyphicon glyphicon-pencil"></i></button>',
            y = '<button title="' + f.locale.btnDelete + '" id="btn-Delete" class="btn btn-danger" type="button" disabled><i class="glyphicon glyphicon-trash"></i></button>',
            E = '<button title="' + f.locale.btnNew + '" id="btn-New" class="btn btn-primary" type="button"><i class="glyphicon glyphicon-plus"></i></button>',
            _ = '<button id="btn-Print" class="btn btn-primary"><span class="glyphicon glyphicon-print visible-xs center-block"></span><span class="hidden-xs">&nbsp;&nbsp;Imprimir</span></button>',
            C = '<button id="btn-Receipt" class="btn btn-primary">Recibo</button>',
            N = '<div id="' + m.attr("id") + '-pbToolbar" class="row hidden-print">';
        N += '<div class="col-lg-12">', N += '<div class="input-group">', N += g, N += '<div name="sectionForButtons" class="input-group-btn"></div>', N += "</div></div>"; {
            var T = '<div id="pagination-row" class="pagination-row text-right"><ul class="pagination pagination-sm" style="margin:0"></ul></div>';
            '<li><a href="#" aria-label="Next"><span aria-hidden="true">' + f.locale.btnPrevious + "</span></a></li>", '<li><a href="#" aria-label="Previous"><span aria-hidden="true">' + f.locale.btnNext + "</span></a></li>", '<li><a href="#" aria-label="First"><span aria-hidden="true">' + f.locale.btnFirst + "</span></a></li>", '<li><a href="#" aria-label="Last"><span aria-hidden="true">' + f.locale.btnLast + "</span></a></li>"
        }
        return this.each(function(x) {
            if (cantidadDeColumnas = t("#" + m.attr("id") + " thead tr:first th").length, t(this).children("tbody").append('<tr name="msgNoData"><td colspan="' + cantidadDeColumnas + '"><i>' + f.locale.msgNoData + "</i></td></tr>"), t("#" + m.attr("id") + ' tbody tr[name="msgNoData"]').hide(), f.selectable && (t(this).children("tbody").css("cursor", "pointer"), t(this).children("tbody").children("tr").on("click", function() {
                    if (t(this).hasClass(f.toolbar.selectedClass)) {
                        var o = 1;
                        e()
                    }
                    m.children("tbody").children("tr").removeClass(f.toolbar.selectedClass), 1 != o && (t(this).addClass(f.toolbar.selectedClass), a())
                })), f.sortable && (m.addClass("sortable"), t("#" + m.attr("id") + " thead").css("cursor", "pointer"), n()), f.toolbar.enabled && ("undefined" == f.toolbar.idToAppend || 0 == f.toolbar.idToAppend ? m.before(N) : t("#" + f.toolbar.idToAppend).append(N), f.toolbar.filterBox && t("#" + m.attr("id") + '-pbToolbar div[name="divForSearchBox"]').prepend(g), f.toolbar.tags.length > 0 && f.toolbar.filterBox && 0 == f.isMobile && (void 0 !== o.toolbar && void 0 !== o.toolbar.tags && (f.toolbar.tags = o.toolbar.tags), f.toolbar.tags.forEach(function(e) {
                    t("#" + m.attr("id") + '-pbToolbar ul[name="sectionForTags"]').append('<li><a name="pbButtonTag"  input-search="search-' + m.attr("id") + '" toSearch="' + e.toSearch + '" href="#">' + e.display + "</a></li>")
                }), t('a[name="pbButtonTag"]').click(function() {
                    var e = t("#" + t(this).attr("input-search")),
                        a = t(this).attr("toSearch");
                    p(e, a)
                }), t('div[name="divForSearchBox"]').addClass("input-group")), t("input[search-in]").keyup(function(e) {
                    13 == e.keyCode && t(this).val().length > 0 && b(), 9 != e.keyCode && 16 != e.keyCode && 17 != e.keyCode && 18 != e.keyCode && 91 != e.keyCode && 32 != e.keyCode && 37 != e.keyCode && 38 != e.keyCode && 39 != e.keyCode && 40 != e.keyCode && p(t(this))
                }), f.toolbar.buttons.length > 0 && f.toolbar.filterBox && (void 0 !== o.toolbar && void 0 !== o.toolbar.buttons && (f.toolbar.buttons = o.toolbar.buttons), f.toolbar.buttons.forEach(function(e) {
                    switch (e) {
                        case "view":
                            r(m, v), t("#btn-View").bind("click", s);
                            break;
                        case "edit":
                            r(m, w), t("#btn-Edit").bind("click", i);
                            break;
                        case "delete":
                            r(m, y), t("#btn-Delete").bind("click", l);
                            break;
                        case "new":
                            r(m, E), t("#btn-New").bind("click", d);
                            break;
                        case "print":
                            r(m, _), t("#btn-Print").bind("click", c);
                            break;
                        case "receipt":
                            r(m, C), t("#btn-Receipt").bind("click", h)
                    }
                }))), f.pagination.enabled) {
                t("#" + m.attr("id")).after(T);
                var k = t("#" + m.attr("id") + " tbody tr").length;
                t("#" + m.attr("id") + " tbody tr").each(function(e) {
                    pagNum = Math.ceil((e + 1) / f.pagination.pageSize), t(this).attr("data-page", pagNum)
                }), t("#" + m.attr("id") + ' tbody tr[name="msgNoData"]').removeAttr("data-page");
                var B = Math.ceil((k - 1) / f.pagination.pageSize);
                for (x = 1; B >= x; x++) t("#pagination-row ul").append('<li><a href="#" data-topage="' + x + '">' + x + "</a></li>");
                u(1)
            }
            t(document).on("click", "ul.pagination a[data-topage]", function() {
                t("#search-" + m.attr("id")).val(""), u(t(this).attr("data-toPage"))
            })
        })
    }, t.fn.pbTable.defaults = {
        selectable: !0,
        sortable: !0,
        highlight: !1,
        toolbar: {
            enabled: !0,
            idToAppend: "undefined",
            filterBox: !0,
            selectedClass: "selected",
            tags: [{
                display: "Todos",
                toSearch: ""
            }],
            buttons: ["view", "edit", "delete", "new", "print", "receipt"]
        },
        pagination: {
            enabled: !1,
            pageSize: 10
        },
		locale:{
			btnView:'View',
			btnEdit:'Edit',
			btnDelete:'Delete',
			btnNew:'New',
			btnPrevious:'Previous page',
			btnNext:'Next page',
			btnFirst:'Fist page',
			btnLast:'Last page',
			placeholderSearchBox:'Search...',
			msgNoData:'No results found.'
		}
    }, t.extend(t.expr[":"], {
        "contains-ci": function(e, a, r) {
            return r[3] = o(r[3]), (o(e.innerHTML) || e.textContent || e.innerText || t(e).text() || "").toLowerCase().indexOf((r[3] || "").toLowerCase()) >= 0
        }
    }), s.guid = 1, l.preventDefault = function() {
        this.returnValue = !1
    }, l.stopPropagation = function() {
        this.cancelBubble = !0
    }, Array.forEach || (Array.forEach = function(t, e, a) {
        for (var o = 0; o < t.length; o++) e.call(a, t[o], o, t)
    }), Function.prototype.forEach = function(t, e, a) {
        for (var o in t) "undefined" == typeof this.prototype[o] && e.call(a, t[o], o, t)
    }, String.forEach = function(t, e, a) {
        Array.forEach(t.split(""), function(o, r) {
            e.call(a, o, r, t)
        })
    };
    var c = function(t, e, a) {
        if (t) {
            var o = Object;
            if (t instanceof Function) o = Function;
            else {
                if (t.forEach instanceof Function) return void t.forEach(e, a);
                "string" == typeof t ? o = String : "number" == typeof t.length && (o = Array)
            }
            o.forEach(t, e, a)
        }
    }
}(jQuery);