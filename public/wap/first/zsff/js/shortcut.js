define(['vue'], function (Vue) {
    'use strict';
    Vue.component('shortcut', {
        data: function () {
            return {
                top: '',
                quick: false
            };
        },
        methods: {
            touchMove: function (event) {
                event.preventDefault();
                var screenHeight = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight,
                    oneTenth = screenHeight / 10,
                    min = oneTenth * 2,
                    max = oneTenth * 8;
                if (min >= event.touches[0].clientY) {
                    this.top = screenHeight / 10 * 2;
                } else if (event.touches[0].clientY >= max) {
                    this.top = screenHeight / 10 * 8;
                } else {
                    this.top = event.touches[0].clientY;
                }
            }
        },
        template: '<div class="shortcut" :style="{top: top + \'px\'}" @touchmove.stop="touchMove">' +
            '<div v-if="quick" class="menu">' +
            '<a href="/wap/index/index.html">' +
            '<img src="/wap/first/zsff/images/shortcut1.png">' +
            '</a>' +
            '<a href="/wap/store/index.html">' +
            '<img src="/wap/first/zsff/images/shortcut2.png">' +
            '</a>' +
            '<a href="/wap/my/index.html">' +
            '<img src="/wap/first/zsff/images/shortcut3.png">' +
            '</a>' +
            '</div>' +
            '<div class="home" @click="quick = !quick">' +
            '<img :src="quick ? \'/wap/first/zsff/images/shortcut-open.gif\' : \'/wap/first/zsff/images/shortcut-close.gif\'">' +
            '</div>' +
            '</div>'
    });
});