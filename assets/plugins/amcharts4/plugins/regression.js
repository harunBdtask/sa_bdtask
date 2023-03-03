/**
 * @license
 * Copyright (c) 2018 amCharts (Antanas Marcelionis, Martynas Majeris)
 *
 * This sofware is provided under multiple licenses. Please see below for
 * links to appropriate usage.
 *
 * Free amCharts linkware license. Details and conditions:
 * https://github.com/amcharts/amcharts4/blob/master/LICENSE
 *
 * One of the amCharts commercial licenses. Details and pricing:
 * https://www.amcharts.com/online-store/
 * https://www.amcharts.com/online-store/licenses-explained/
 *
 * If in doubt, contact amCharts at contact@amcharts.com
 *
 * PLEASE DO NOT REMOVE THIS COPYRIGHT NOTICE.
 * @hidden
 */
am4internal_webpackJsonp(["6b72"],{"1NAr":function(t,r,e){var n,i,o;!function(e,a){i=[t],void 0===(o="function"==typeof(n=a)?n.apply(r,i):n)||(t.exports=o)}(0,function(t){"use strict";var r=Object.assign||function(t){for(var r=1;r<arguments.length;r++){var e=arguments[r];for(var n in e)Object.prototype.hasOwnProperty.call(e,n)&&(t[n]=e[n])}return t};var e={order:2,precision:2,period:null};function n(t,r){var e=[],n=[];t.forEach(function(t,i){null!==t[1]&&(n.push(t),e.push(r[i]))});var i=n.reduce(function(t,r){return t+r[1]},0)/n.length,o=n.reduce(function(t,r){var e=r[1]-i;return t+e*e},0);return 1-n.reduce(function(t,r,n){var i=e[n],o=r[1]-i[1];return t+o*o},0)/o}function i(t,r){var e=Math.pow(10,r);return Math.round(t*e)/e}var o={linear:function(t,r){for(var e=[0,0,0,0,0],o=0,a=0;a<t.length;a++)null!==t[a][1]&&(o++,e[0]+=t[a][0],e[1]+=t[a][1],e[2]+=t[a][0]*t[a][0],e[3]+=t[a][0]*t[a][1],e[4]+=t[a][1]*t[a][1]);var s=o*e[2]-e[0]*e[0],u=o*e[3]-e[0]*e[1],p=0===s?0:i(u/s,r.precision),c=i(e[1]/o-p*e[0]/o,r.precision),l=function(t){return[i(t,r.precision),i(p*t+c,r.precision)]},h=t.map(function(t){return l(t[0])});return{points:h,predict:l,equation:[p,c],r2:i(n(t,h),r.precision),string:0===c?"y = "+p+"x":"y = "+p+"x + "+c}},exponential:function(t,r){for(var e=[0,0,0,0,0,0],o=0;o<t.length;o++)null!==t[o][1]&&(e[0]+=t[o][0],e[1]+=t[o][1],e[2]+=t[o][0]*t[o][0]*t[o][1],e[3]+=t[o][1]*Math.log(t[o][1]),e[4]+=t[o][0]*t[o][1]*Math.log(t[o][1]),e[5]+=t[o][0]*t[o][1]);var a=e[1]*e[2]-e[5]*e[5],s=Math.exp((e[2]*e[3]-e[5]*e[4])/a),u=(e[1]*e[4]-e[5]*e[3])/a,p=i(s,r.precision),c=i(u,r.precision),l=function(t){return[i(t,r.precision),i(p*Math.exp(c*t),r.precision)]},h=t.map(function(t){return l(t[0])});return{points:h,predict:l,equation:[p,c],string:"y = "+p+"e^("+c+"x)",r2:i(n(t,h),r.precision)}},logarithmic:function(t,r){for(var e=[0,0,0,0],o=t.length,a=0;a<o;a++)null!==t[a][1]&&(e[0]+=Math.log(t[a][0]),e[1]+=t[a][1]*Math.log(t[a][0]),e[2]+=t[a][1],e[3]+=Math.pow(Math.log(t[a][0]),2));var s=i((o*e[1]-e[2]*e[0])/(o*e[3]-e[0]*e[0]),r.precision),u=i((e[2]-s*e[0])/o,r.precision),p=function(t){return[i(t,r.precision),i(i(u+s*Math.log(t),r.precision),r.precision)]},c=t.map(function(t){return p(t[0])});return{points:c,predict:p,equation:[u,s],string:"y = "+u+" + "+s+" ln(x)",r2:i(n(t,c),r.precision)}},power:function(t,r){for(var e=[0,0,0,0,0],o=t.length,a=0;a<o;a++)null!==t[a][1]&&(e[0]+=Math.log(t[a][0]),e[1]+=Math.log(t[a][1])*Math.log(t[a][0]),e[2]+=Math.log(t[a][1]),e[3]+=Math.pow(Math.log(t[a][0]),2));var s=(o*e[1]-e[0]*e[2])/(o*e[3]-Math.pow(e[0],2)),u=(e[2]-s*e[0])/o,p=i(Math.exp(u),r.precision),c=i(s,r.precision),l=function(t){return[i(t,r.precision),i(i(p*Math.pow(t,c),r.precision),r.precision)]},h=t.map(function(t){return l(t[0])});return{points:h,predict:l,equation:[p,c],string:"y = "+p+"x^"+c,r2:i(n(t,h),r.precision)}},polynomial:function(t,r){for(var e=[],o=[],a=0,s=0,u=t.length,p=r.order+1,c=0;c<p;c++){for(var l=0;l<u;l++)null!==t[l][1]&&(a+=Math.pow(t[l][0],c)*t[l][1]);e.push(a),a=0;for(var h=[],f=0;f<p;f++){for(var d=0;d<u;d++)null!==t[d][1]&&(s+=Math.pow(t[d][0],c+f));h.push(s),s=0}o.push(h)}o.push(e);for(var v=function(t,r){for(var e=t,n=t.length-1,i=[r],o=0;o<n;o++){for(var a=o,s=o+1;s<n;s++)Math.abs(e[o][s])>Math.abs(e[o][a])&&(a=s);for(var u=o;u<n+1;u++){var p=e[u][o];e[u][o]=e[u][a],e[u][a]=p}for(var c=o+1;c<n;c++)for(var l=n;l>=o;l--)e[l][c]-=e[l][o]*e[o][c]/e[o][o]}for(var h=n-1;h>=0;h--){for(var f=0,d=h+1;d<n;d++)f+=e[d][h]*i[d];i[h]=(e[n][h]-f)/e[h][h]}return i}(o,p).map(function(t){return i(t,r.precision)}),g=function(t){return[i(t,r.precision),i(v.reduce(function(r,e,n){return r+e*Math.pow(t,n)},0),r.precision)]},y=t.map(function(t){return g(t[0])}),m="y = ",_=v.length-1;_>=0;_--)m+=_>1?v[_]+"x^"+_+" + ":1===_?v[_]+"x + ":v[_];return{string:m,points:y,predict:g,equation:[].concat(function(t){if(Array.isArray(t)){for(var r=0,e=Array(t.length);r<t.length;r++)e[r]=t[r];return e}return Array.from(t)}(v)).reverse(),r2:i(n(t,y),r.precision)}}};t.exports=Object.keys(o).reduce(function(t,n){return r({_round:i},t,function(t,r,e){return r in t?Object.defineProperty(t,r,{value:e,enumerable:!0,configurable:!0,writable:!0}):t[r]=e,t}({},n,function(t,i){return o[n](t,r({},e,i))}))},{})})},oUDf:function(t,r,e){"use strict";Object.defineProperty(r,"__esModule",{value:!0});var n={};e.d(n,"Regression",function(){return l});var i=e("m4/l"),o=e("1NAr"),a=e("Iz1H"),s=e("aCit"),u=e("o0Lc"),p=e("Qkdp"),c=e("Mtpk"),l=function(t){function r(){var r=t.call(this)||this;return r.events=new u.a,r._method="linear",r._options={},r._simplify=!1,r._reorder=!1,r._skipValidatedEvent=!1,r}return i.c(r,t),r.prototype.init=function(){t.prototype.init.call(this),this.processSeries()},r.prototype.processSeries=function(){var t=this;this.invalidateData(),this._disposers.push(this.target.events.on("beforedatavalidated",function(r){t._skipValidatedEvent?t._skipValidatedEvent=!1:t.calcData()})),this.target.data&&this.target.data.length&&(this._originalData=this.target.data),this.target.adapter.add("data",function(){return void 0===t._data&&t.calcData(),t._data})},r.prototype.invalidateData=function(){this._data=void 0},r.prototype.calcData=function(){this._data=[];var t=this.target,r=this._originalData;r&&0!=r.length||(r=this.target.baseSprite.data);for(var e=[],n={},i=0,a=0;a<r.length;a++){var s=t.dataFields.valueX?r[a][t.dataFields.valueX]:a,u=t.dataFields.valueY?r[a][t.dataFields.valueY]:a;c.hasValue(s)&&c.hasValue(u)&&(e.push([s,u]),n[i]=a,i++)}var l=[];switch(this.method){case"polynomial":l=o.polynomial(e,this.options);break;default:l=o.linear(e,this.options)}this.result=l,this.events.dispatchImmediately("processed",{type:"processed",target:this}),this.reorder&&l.points.sort(function(t,r){return t[0]>r[0]?-1:t[0]<r[0]?1:0}),this._data=[];var h,f=function(t){d.simplify&&t&&(t=l.points.length-1);var e={},i=n[t];p.each(d.target.dataFields,function(n,o){e[o]="valueX"==n?l.points[t][0]:"valueY"==n?l.points[t][1]:r[i][o]}),d._data.push(e),h=t},d=this;for(a=0;a<l.points.length;a++)f(a),a=h},Object.defineProperty(r.prototype,"method",{get:function(){return this._method},set:function(t){this._method!=t&&(this._method=t,this.invalidateData())},enumerable:!0,configurable:!0}),Object.defineProperty(r.prototype,"options",{get:function(){return this._options},set:function(t){this._options!=t&&(this._options=t,this.invalidateData())},enumerable:!0,configurable:!0}),Object.defineProperty(r.prototype,"simplify",{get:function(){return this._simplify},set:function(t){this._simplify!=t&&(this._simplify=t,this.invalidateData())},enumerable:!0,configurable:!0}),Object.defineProperty(r.prototype,"reorder",{get:function(){return this._reorder},set:function(t){this._reorder!=t&&(this._reorder=t,this.invalidateData())},enumerable:!0,configurable:!0}),r}(a.a);s.b.registeredClasses.Regression=l,window.am4plugins_regression=n}},["oUDf"]);
//#  =regression.js.map