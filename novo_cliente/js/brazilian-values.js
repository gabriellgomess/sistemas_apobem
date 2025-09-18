! function (e, r) {
    "object" == typeof exports && "undefined" != typeof module ? r(exports) : "function" == typeof define && define.amd ? define(["exports"], r) : r((e = "undefined" != typeof globalThis ? globalThis : e || self).BrazilianValues = {})
}(this, (function (e) {
    "use strict";
    var r = function (e) {
            return new Date(e.year, e.month, e.date, e.hours, e.minutes, e.seconds)
        },
        t = function (e, t) {
            return void 0 === t && (t = r(e)), t.getDate() === e.date && t.getMonth() === e.month && t.getFullYear() === e.year && t.getHours() === e.hours && t.getMinutes() === e.minutes && t.getSeconds() === e.seconds
        },
        n = function (e) {
            var r = /^(\d{2})\/(\d{2})\/(\d{4})( (\d{2}):(\d{2})(:(\d{2}))?)?$/.exec(e),
                t = r[1],
                n = r[2],
                o = r[3],
                u = r[5],
                d = r[6],
                i = r[8];
            return {
                date: parseInt(t, 10),
                year: parseInt(o, 10),
                month: parseInt(n, 10) - 1,
                hours: parseInt(null != u ? u : 0, 10),
                minutes: parseInt(null != d ? d : 0, 10),
                seconds: parseInt(null != i ? i : 0, 10)
            }
        },
        o = /^\d{2}\/\d{2}\/\d{4}( \d{2}:\d{2}(:\d{2})?)?$/,
        u = /^(\d{8}|\d{2}\.?\d{3}\-\d{3})$/,
        d = function (e, r) {
            return r.reduce((function (r, t, n) {
                var o = r[0],
                    u = r[1];
                return [0 === n ? 0 : o + e[n - 1] * t, u + e[n] * t]
            }), [0, 0])
        },
        i = function (e) {
            return e % 11 < 2 ? 0 : 11 - e % 11
        },
        a = function (e) {
            return e.every((function (r) {
                return e[0] === r
            }))
        },
        c = /\D/g,
        f = function (e) {
            return e.replace(c, "")
        },
        l = function (e) {
            return f(e).split("").map(Number)
        },
        s = /^(\d{14}|\d{2}\.\d{3}\.\d{3}\/\d{4}\-\d{2})$/,
        p = function (e) {
            if (!s.test(e)) return !1;
            var r = l(e);
            if (a(r)) return !1;
            var t = d(r, [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2]);
            return r[12] === i(t[0]) && r[13] === i(t[1])
        },
        $ = /^(\d{11}|\d{3}\.\d{3}\.\d{3}\-\d{2})$/,
        g = function (e) {
            if (!$.test(e)) return !1;
            var r = l(e);
            if (a(r)) return !1;
            var t = d(r, [11, 10, 9, 8, 7, 6, 5, 4, 3, 2]);
            return r[9] === i(t[0]) && r[10] === i(t[1])
        },
        m = /^\d{2}\/\d{2}\/\d{4}((\s)?(\d{2}:\d{2}:\d{2}))?$/,
        v = ["11", "12", "13", "14", "15", "16", "17", "18", "19", "21", "22", "24", "27", "28", "31", "32", "33", "34", "35", "37", "38", "41", "42", "43", "44", "45", "46", "47", "48", "49", "51", "53", "54", "55", "61", "62", "63", "64", "65", "66", "67", "68", "69", "71", "73", "74", "75", "77", "79", "81", "82", "83", "84", "85", "86", "87", "88", "89", "91", "92", "93", "94", "95", "96", "97", "98", "99"],
        h = function (e) {
            return -1 !== v.indexOf(e)
        },
        T = /^(\+55)? ?\(?(\d{2})?\)? ?9? ?\d{4}[-| ]?\d{4}$/,
        C = [$, s],
        P = function (e) {
            return e.replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.")
        },
        b = ["cnpj", "cpf", "ltda", "qp", "tv"],
        w = ["a", "com", "da", "das", "de", "do", "dos", "e", "em", "i", "na", "nas", "no", "nos", "o", "por", "sem", "u"],
        D = function (e) {
            return f(e).replace(/(\d{2})(\d)/, "$1.$2").replace(/(\d{3})(\d)/, "$1.$2").replace(/(\d{3})(\d)/, "$1/$2").replace(/(\d{4})(\d{1,2})$/, "$1-$2")
        },
        x = function (e) {
            return f(e).replace(/(\d{3})(\d)/, "$1.$2").replace(/(\d{3})(\d)/, "$1.$2").replace(/(\d{3})(\d{1,2})$/, "$1-$2")
        },
        y = function (e, r) {
            for (var t = e.toString(10); t.length < r;) t = "0" + t;
            return t
        },
        N = function (e) {
            return y(e.getDate(), 2) + "/" + y(e.getMonth() + 1, 2) + "/" + y(e.getFullYear(), 4)
        };
    e.formatToBRL = function (e) {
        var r = Number(e).toFixed(2).replace(".", ",");
        return "R$ " + P(r)
    }, e.formatToCEP = function (e) {
        return f(e).replace(/(\d{5})(\d{1,3})/, "$1-$2")
    }, e.formatToCNPJ = D, e.formatToCPF = x, e.formatToCPFOrCNPJ = function (e) {
        return function (e) {
            var r, t;
            return (null !== (t = null === (r = e.match(/\d/g)) || void 0 === r ? void 0 : r.length) && void 0 !== t ? t : 0) <= 11
        }(e) ? x(e) : D(e)
    }, e.formatToCapitalized = function (e, r) {
        var t, n = void 0 === r ? {} : r,
            o = n.wordsToKeepLowerCase,
            u = void 0 === o ? w : o,
            d = n.wordsToKeepUpperCase,
            i = void 0 === d ? b : d,
            a = n.trimTrailingWhiteSpaces;
        return (t = void 0 === a || a ? function (e) {
            return e.trim().replace(/\s+/g, " ")
        }(e) : e, t ? t.split(/\s+/) : []).map((function (e, r, t) {
            var n = e && 0 === r || !t[0] && 1 === r,
                o = e.toLocaleLowerCase();
            return n || -1 === u.indexOf(o) ? -1 !== i.indexOf(o) ? e.toLocaleUpperCase() : function (e) {
                return e.charAt(0).toLocaleUpperCase() + e.substr(1).toLocaleLowerCase()
            }(e) : o
        })).join(" ")
    }, e.formatToDate = N, e.formatToDateTime = function (e) {
        return N(e) + " " + y(e.getHours(), 2) + ":" + y(e.getMinutes(), 2)
    }, e.formatToGenericPhone = function (e, r) {
        void 0 === r && (r = 2);
        var t = f(e);
        if (8 === t.length) return t.replace(/(^\d{4})(\d{4}$)/gi, "$1-$2");
        if (9 === t.length) return t.replace(/(^\d{5})(\d{4}$)/gi, "$1-$2");
        if (10 === t.length) return t.replace(/(^\d{2})(\d{4})(\d{4}$)/gi, "($1) $2-$3");
        if (11 === t.length) return t.replace(/(^\d{2})(\d{4,5})(\d{4}$)/gi, "($1) $2-$3");
        if (12 === t.length) return t.replace(/(^\d{3})(\d{5})(\d{4}$)/gi, "$1 $2-$3");
        var n = new RegExp("([0-9]{" + r + "})([0-9][0-9])([0-9]{5})([0-9]{4})", "gi");
        return t.replace(n, "+$1 $2 $3-$4")
    }, e.formatToList = function (e) {
        if (0 === e.length) return "";
        if (1 === e.length) return e[0];
        var r = function (e) {
                return [e.slice(0, e.length - 1), e[e.length - 1]]
            }(e),
            t = r[0],
            n = r[1];
        return t.join(", ") + " e " + n
    }, e.formatToNumber = function (e) {
        var r = Number(e).toString(10).split("."),
            t = r[0],
            n = r[1];
        return n ? P(t) + "," + n : P(t)
    }, e.formatToPhone = function (e) {
        return f(e).replace(/(\d{1,2})/, "($1").replace(/(\(\d{2})(\d{1,4})/, "$1) $2").replace(/( \d{4})(\d{1,4})/, "$1-$2").replace(/( \d{1})(\d{3})(?:-)(\d{1})(\d{4})/, "$1 $2$3-$4")
    }, e.formatToRG = function (e, r) {
        return "RJ" !== r && "SP" !== r ? e : e.toUpperCase().replace(/[^\d|A|B|X]/g, "").replace(/(\d{2})(\d)/, "$1.$2").replace(/(\d{3})(\d)/, "$1.$2").replace(/(\d{3})([\d|A|B|X]{1})$/, "$1-$2")
    }, e.isCEP = function (e) {
        return u.test(e)
    }, e.isCNPJ = p, e.isCPF = g, e.isCPFOrCNPJ = function (e) {
        var r = C.map((function (r) {
            return r.test(e)
        }));
        return !!r.includes(!0) && (r[0] ? g(e) : p(e))
    }, e.isDDD = h, e.isDate = function (e) {
        return m.test(e) && t(n(e))
    }, e.isPhone = function (e) {
        if (!T.test(e)) return !1;
        var r = T.exec(e)[2];
        return !r || h(r)
    }, e.parseToArray = function (e) {
        if (!e.trim()) return [];
        var r = e.split(" e ");
        return 1 === r.length ? r : r[0].split(", ").concat(r[1])
    }, e.parseToDate = function (e) {
        if (!o.test(e)) throw new Error('Value "' + e + '" does not match format.');
        var u = n(e),
            d = r(u);
        if (!t(u, d)) throw new Error('Value "' + e + '" is an invalid date.');
        return d
    }, e.parseToNumber = function (e) {
        return Number(e.replace(/\./g, "").replace(",", "."))
    }, Object.defineProperty(e, "__esModule", {
        value: !0
    })
}));
//# sourceMappingURL=brazilian-values.umd.min.js.map