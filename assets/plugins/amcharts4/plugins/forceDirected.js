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
am4internal_webpackJsonp(["3741"],{"eUZ+":function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var i={};n.d(i,"ForceDirectedLink",function(){return c}),n.d(i,"ForceDirectedTreeDataItem",function(){return wt}),n.d(i,"ForceDirectedTree",function(){return kt}),n.d(i,"ForceDirectedNode",function(){return m}),n.d(i,"ForceDirectedSeriesDataItem",function(){return bt}),n.d(i,"ForceDirectedSeries",function(){return _t});var r=n("m4/l"),o=n("Vs7R"),a=n("aCit"),s=n("MIZb"),l=n("hGwe"),c=function(t){function e(){var e=t.call(this)||this;e.className="ForceDirectedLink";var n=new s.a;return e.fillOpacity=0,e.strokeOpacity=.5,e.stroke=n.getFor("grid"),e.isMeasured=!1,e.nonScalingStroke=!0,e.interactionsEnabled=!1,e.distance=1.5,e.strength=1,e.applyTheme(),e}return r.c(e,t),e.prototype.validate=function(){t.prototype.validate.call(this);var e=this.source,n=this.target;e&&n&&(this.path=l.moveTo({x:e.pixelX,y:e.pixelY})+l.lineTo({x:n.pixelX,y:n.pixelY}),e.isHidden||n.isHidden||e.isHiding||n.isHiding?this.hide():this.show())},Object.defineProperty(e.prototype,"source",{get:function(){return this._source},set:function(t){t&&(this._source=t,this._disposers.push(t.events.on("positionchanged",this.invalidate,this,!1)))},enumerable:!0,configurable:!0}),Object.defineProperty(e.prototype,"target",{get:function(){return this._target},set:function(t){t&&(this._target=t,this._disposers.push(t.events.on("positionchanged",this.invalidate,this,!1)))},enumerable:!0,configurable:!0}),Object.defineProperty(e.prototype,"distance",{get:function(){return this.adapter.isEnabled("distance")?this.adapter.apply("distance",this.properties.distance):this.properties.distance},set:function(t){this.setPropertyValue("distance",t)},enumerable:!0,configurable:!0}),Object.defineProperty(e.prototype,"strength",{get:function(){return this.adapter.isEnabled("strength")?this.adapter.apply("strength",this.properties.strength):this.properties.strength},set:function(t){this.setPropertyValue("strength",t)},enumerable:!0,configurable:!0}),e}(o.a);a.b.registeredClasses.ForceDirectedLink=c;var u=n("2I/e"),h=n("aM7D"),d=n("vMqJ"),f=n("C6dT"),p=n("FzPm"),y=n("p9TX"),v=n("Mtpk"),g=n("+qIf"),m=function(t){function e(){var e=t.call(this)||this;e.className="ForceDirectedNode",e.applyOnClones=!0,e.togglable=!0,e.draggable=!0,e.setStateOnChildren=!0,e.isActive=!1,e.expandAll=!0,e.linksWith=new g.a,e._disposers.push(new g.b(e.linksWith)),e.events.on("dragstart",function(){e.dataItem.component&&e.dataItem.component.nodeDragStarted()},e,!1),e.events.on("drag",function(){e.updateSimulation()},e,!1);var n=e.createChild(p.a);n.shouldClone=!1,n.strokeWidth=2;var i=(new s.a).getFor("background");n.fill=i,e.outerCircle=n,n.states.create("hover").properties.scale=1.1;var r=n.states.create("active");r.properties.scale=1.1,r.properties.visible=!0,n.states.create("hoverActive").properties.scale=1;var o=e.createChild(p.a);o.states.create("active").properties.visible=!0,o.shouldClone=!1,o.interactionsEnabled=!1,o.hiddenState.properties.radius=.01,o.events.on("validated",e.updateSimulation,e,!1),o.hiddenState.properties.visible=!0,e.circle=o,e.addDisposer(o.events.on("validated",e.updateLabelSize,e,!1)),e._disposers.push(e.circle);var a=e.createChild(y.a);return a.shouldClone=!1,a.horizontalCenter="middle",a.verticalCenter="middle",a.fill=i,a.strokeOpacity=0,a.interactionsEnabled=!1,a.textAlign="middle",a.textValign="middle",e.label=a,e.adapter.add("tooltipY",function(t,e){return-e.circle.pixelRadius}),e}return r.c(e,t),e.prototype.updateLabelSize=function(){if(this.label.text){var t=this.circle,e=t.pixelRadius,n=t.defaultState.properties.radius;v.isNumber(n)&&(e=n),this.label.width=2*e,this.label.height=2*e}},e.prototype.copyFrom=function(e){t.prototype.copyFrom.call(this,e),this.circle&&this.circle.copyFrom(e.circle),this.label&&this.label.copyFrom(e.label),this.outerCircle&&this.outerCircle.copyFrom(e.outerCircle)},e.prototype.setActive=function(e){var n=this;t.prototype.setActive.call(this,e);var i=this.dataItem;if(i){var r=i.children,o=i.component;o.dataItemsInvalid||(e&&r&&!i.childrenInited&&(o.initNode(i),o.updateNodeList()),e?(r&&r.each(function(t){t.node.show(),t.node.interactionsEnabled=!0,t.parentLink&&t.parentLink.show(),n.expandAll?t.node.isActive=!0:t.node.isActive=!1}),i.dispatchVisibility(!0)):(r&&r.each(function(t){t.parentLink&&t.parentLink.hide(),t.node.isActive=!1,t.node.interactionsEnabled=!1,t.node.hide()}),i.dispatchVisibility(!1)))}this.updateSimulation()},e.prototype.updateSimulation=function(){var t=this.dataItem;t&&t.component&&t.component.restartSimulation()},Object.defineProperty(e.prototype,"expandAll",{get:function(){return this.getPropertyValue("expandAll")},set:function(t){this.setPropertyValue("expandAll",t)},enumerable:!0,configurable:!0}),e.prototype.linkWith=function(t,e){var n=this.linksWith.getKey(t.uid);if(n||(n=t.linksWith.getKey(this.uid)),!n){var i=this.dataItem,r=i.component;(n=r.links.create()).parent=r,n.zIndex=-1,n.source=this,n.target=t,n.stroke=i.node.fill,n.dataItem=t.dataItem,v.isNumber(e)&&(n.strength=e);var o=r.nodes.indexOf(i.node),a=r.nodes.indexOf(t);r.forceLinks.push({source:o,target:a}),r.updateNodeList(),i.childLinks.push(n),this.linksWith.setKey(t.uid,n)}return n},e.prototype.unlinkWith=function(t){this.linksWith.removeKey(t.uid)},e}(f.a);a.b.registeredClasses.ForceDirectedNode=m;var x=n("hD5A"),b=n("DHte");function _(t,e,n,i){if(isNaN(e)||isNaN(n))return t;var r,o,a,s,l,c,u,h,d,f=t._root,p={data:i},y=t._x0,v=t._y0,g=t._x1,m=t._y1;if(!f)return t._root=p,t;for(;f.length;)if((c=e>=(o=(y+g)/2))?y=o:g=o,(u=n>=(a=(v+m)/2))?v=a:m=a,r=f,!(f=f[h=u<<1|c]))return r[h]=p,t;if(s=+t._x.call(null,f.data),l=+t._y.call(null,f.data),e===s&&n===l)return p.next=f,r?r[h]=p:t._root=p,t;do{r=r?r[h]=new Array(4):t._root=new Array(4),(c=e>=(o=(y+g)/2))?y=o:g=o,(u=n>=(a=(v+m)/2))?v=a:m=a}while((h=u<<1|c)==(d=(l>=a)<<1|s>=o));return r[d]=f,r[h]=p,t}var w=function(t,e,n,i,r){this.node=t,this.x0=e,this.y0=n,this.x1=i,this.y1=r};function k(t){return t[0]}function S(t){return t[1]}function I(t,e,n){var i=new N(null==e?k:e,null==n?S:n,NaN,NaN,NaN,NaN);return null==t?i:i.addAll(t)}function N(t,e,n,i,r,o){this._x=t,this._y=e,this._x0=n,this._y0=i,this._x1=r,this._y1=o,this._root=void 0}function O(t){for(var e={data:t.data},n=e;t=t.next;)n=n.next={data:t.data};return e}var A=I.prototype=N.prototype;A.copy=function(){var t,e,n=new N(this._x,this._y,this._x0,this._y0,this._x1,this._y1),i=this._root;if(!i)return n;if(!i.length)return n._root=O(i),n;for(t=[{source:i,target:n._root=new Array(4)}];i=t.pop();)for(var r=0;r<4;++r)(e=i.source[r])&&(e.length?t.push({source:e,target:i.target[r]=new Array(4)}):i.target[r]=O(e));return n},A.add=function(t){var e=+this._x.call(null,t),n=+this._y.call(null,t);return _(this.cover(e,n),e,n,t)},A.addAll=function(t){var e,n,i,r,o=t.length,a=new Array(o),s=new Array(o),l=1/0,c=1/0,u=-1/0,h=-1/0;for(n=0;n<o;++n)isNaN(i=+this._x.call(null,e=t[n]))||isNaN(r=+this._y.call(null,e))||(a[n]=i,s[n]=r,i<l&&(l=i),i>u&&(u=i),r<c&&(c=r),r>h&&(h=r));if(l>u||c>h)return this;for(this.cover(l,c).cover(u,h),n=0;n<o;++n)_(this,a[n],s[n],t[n]);return this},A.cover=function(t,e){if(isNaN(t=+t)||isNaN(e=+e))return this;var n=this._x0,i=this._y0,r=this._x1,o=this._y1;if(isNaN(n))r=(n=Math.floor(t))+1,o=(i=Math.floor(e))+1;else{for(var a,s,l=r-n,c=this._root;n>t||t>=r||i>e||e>=o;)switch(s=(e<i)<<1|t<n,(a=new Array(4))[s]=c,c=a,l*=2,s){case 0:r=n+l,o=i+l;break;case 1:n=r-l,o=i+l;break;case 2:r=n+l,i=o-l;break;case 3:n=r-l,i=o-l}this._root&&this._root.length&&(this._root=c)}return this._x0=n,this._y0=i,this._x1=r,this._y1=o,this},A.data=function(){var t=[];return this.visit(function(e){if(!e.length)do{t.push(e.data)}while(e=e.next)}),t},A.extent=function(t){return arguments.length?this.cover(+t[0][0],+t[0][1]).cover(+t[1][0],+t[1][1]):isNaN(this._x0)?void 0:[[this._x0,this._y0],[this._x1,this._y1]]},A.find=function(t,e,n){var i,r,o,a,s,l,c,u=this._x0,h=this._y0,d=this._x1,f=this._y1,p=[],y=this._root;for(y&&p.push(new w(y,u,h,d,f)),null==n?n=1/0:(u=t-n,h=e-n,d=t+n,f=e+n,n*=n);l=p.pop();)if(!(!(y=l.node)||(r=l.x0)>d||(o=l.y0)>f||(a=l.x1)<u||(s=l.y1)<h))if(y.length){var v=(r+a)/2,g=(o+s)/2;p.push(new w(y[3],v,g,a,s),new w(y[2],r,g,v,s),new w(y[1],v,o,a,g),new w(y[0],r,o,v,g)),(c=(e>=g)<<1|t>=v)&&(l=p[p.length-1],p[p.length-1]=p[p.length-1-c],p[p.length-1-c]=l)}else{var m=t-+this._x.call(null,y.data),x=e-+this._y.call(null,y.data),b=m*m+x*x;if(b<n){var _=Math.sqrt(n=b);u=t-_,h=e-_,d=t+_,f=e+_,i=y.data}}return i},A.remove=function(t){if(isNaN(o=+this._x.call(null,t))||isNaN(a=+this._y.call(null,t)))return this;var e,n,i,r,o,a,s,l,c,u,h,d,f=this._root,p=this._x0,y=this._y0,v=this._x1,g=this._y1;if(!f)return this;if(f.length)for(;;){if((c=o>=(s=(p+v)/2))?p=s:v=s,(u=a>=(l=(y+g)/2))?y=l:g=l,e=f,!(f=f[h=u<<1|c]))return this;if(!f.length)break;(e[h+1&3]||e[h+2&3]||e[h+3&3])&&(n=e,d=h)}for(;f.data!==t;)if(i=f,!(f=f.next))return this;return(r=f.next)&&delete f.next,i?(r?i.next=r:delete i.next,this):e?(r?e[h]=r:delete e[h],(f=e[0]||e[1]||e[2]||e[3])&&f===(e[3]||e[2]||e[1]||e[0])&&!f.length&&(n?n[d]=f:this._root=f),this):(this._root=r,this)},A.removeAll=function(t){for(var e=0,n=t.length;e<n;++e)this.remove(t[e]);return this},A.root=function(){return this._root},A.size=function(){var t=0;return this.visit(function(e){if(!e.length)do{++t}while(e=e.next)}),t},A.visit=function(t){var e,n,i,r,o,a,s=[],l=this._root;for(l&&s.push(new w(l,this._x0,this._y0,this._x1,this._y1));e=s.pop();)if(!t(l=e.node,i=e.x0,r=e.y0,o=e.x1,a=e.y1)&&l.length){var c=(i+o)/2,u=(r+a)/2;(n=l[3])&&s.push(new w(n,c,u,o,a)),(n=l[2])&&s.push(new w(n,i,u,c,a)),(n=l[1])&&s.push(new w(n,c,r,o,u)),(n=l[0])&&s.push(new w(n,i,r,c,u))}return this},A.visitAfter=function(t){var e,n=[],i=[];for(this._root&&n.push(new w(this._root,this._x0,this._y0,this._x1,this._y1));e=n.pop();){var r=e.node;if(r.length){var o,a=e.x0,s=e.y0,l=e.x1,c=e.y1,u=(a+l)/2,h=(s+c)/2;(o=r[0])&&n.push(new w(o,a,s,u,h)),(o=r[1])&&n.push(new w(o,u,s,l,h)),(o=r[2])&&n.push(new w(o,a,h,u,c)),(o=r[3])&&n.push(new w(o,u,h,l,c))}i.push(e)}for(;e=i.pop();)t(e.node,e.x0,e.y0,e.x1,e.y1);return this},A.x=function(t){return arguments.length?(this._x=t,this):this._x},A.y=function(t){return arguments.length?(this._y=t,this):this._y};var P=function(t){return function(){return t}},D=function(){return 1e-6*(Math.random()-.5)};function L(t){return t.x+t.vx}function F(t){return t.y+t.vy}function M(t){return(M="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t})(t)}function T(t){return t.index}function V(t,e){var n=t.get(e);if(!n)throw new Error("missing: "+e);return n}var j={value:function(){}};function C(){for(var t,e=0,n=arguments.length,i={};e<n;++e){if(!(t=arguments[e]+"")||t in i)throw new Error("illegal type: "+t);i[t]=[]}return new W(i)}function W(t){this._=t}function R(t,e){for(var n,i=0,r=t.length;i<r;++i)if((n=t[i]).name===e)return n.value}function E(t,e,n){for(var i=0,r=t.length;i<r;++i)if(t[i].name===e){t[i]=j,t=t.slice(0,i).concat(t.slice(i+1));break}return null!=n&&t.push({name:e,value:n}),t}W.prototype=C.prototype={constructor:W,on:function(t,e){var n,i=this._,r=function(t,e){return t.trim().split(/^|\s+/).map(function(t){var n="",i=t.indexOf(".");if(i>=0&&(n=t.slice(i+1),t=t.slice(0,i)),t&&!e.hasOwnProperty(t))throw new Error("unknown type: "+t);return{type:t,name:n}})}(t+"",i),o=-1,a=r.length;if(!(arguments.length<2)){if(null!=e&&"function"!=typeof e)throw new Error("invalid callback: "+e);for(;++o<a;)if(n=(t=r[o]).type)i[n]=E(i[n],t.name,e);else if(null==e)for(n in i)i[n]=E(i[n],t.name,null);return this}for(;++o<a;)if((n=(t=r[o]).type)&&(n=R(i[n],t.name)))return n},copy:function(){var t={},e=this._;for(var n in e)t[n]=e[n].slice();return new W(t)},call:function(t,e){if((n=arguments.length-2)>0)for(var n,i,r=new Array(n),o=0;o<n;++o)r[o]=arguments[o+2];if(!this._.hasOwnProperty(t))throw new Error("unknown type: "+t);for(o=0,n=(i=this._[t]).length;o<n;++o)i[o].value.apply(e,r)},apply:function(t,e,n){if(!this._.hasOwnProperty(t))throw new Error("unknown type: "+t);for(var i=this._[t],r=0,o=i.length;r<o;++r)i[r].value.apply(e,n)}};var q=C;function z(t){return(z="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t})(t)}var H,B,K=0,X=0,Y=0,J=1e3,U=0,Z=0,G=0,Q="object"===("undefined"==typeof performance?"undefined":z(performance))&&performance.now?performance:Date,$="object"===("undefined"==typeof window?"undefined":z(window))&&window.requestAnimationFrame?window.requestAnimationFrame.bind(window):function(t){setTimeout(t,17)};function tt(){return Z||($(et),Z=Q.now()+G)}function et(){Z=0}function nt(){this._call=this._time=this._next=null}function it(t,e,n){var i=new nt;return i.restart(t,e,n),i}function rt(){Z=(U=Q.now())+G,K=X=0;try{!function(){tt(),++K;for(var t,e=H;e;)(t=Z-e._time)>=0&&e._call.call(null,t),e=e._next;--K}()}finally{K=0,function(){var t,e,n=H,i=1/0;for(;n;)n._call?(i>n._time&&(i=n._time),t=n,n=n._next):(e=n._next,n._next=null,n=t?t._next=e:H=e);B=t,at(i)}(),Z=0}}function ot(){var t=Q.now(),e=t-U;e>J&&(G-=e,U=t)}function at(t){K||(X&&(X=clearTimeout(X)),t-Z>24?(t<1/0&&(X=setTimeout(rt,t-Q.now()-G)),Y&&(Y=clearInterval(Y))):(Y||(U=Q.now(),Y=setInterval(ot,J)),K=1,$(rt)))}nt.prototype=it.prototype={constructor:nt,restart:function(t,e,n){if("function"!=typeof t)throw new TypeError("callback is not a function");n=(null==n?tt():+n)+(null==e?0:+e),this._next||B===this||(B?B._next=this:H=this,B=this),this._call=t,this._time=n,at()},stop:function(){this._call&&(this._call=null,this._time=1/0,at())}};function st(t){return t.x}function lt(t){return t.y}var ct=10,ut=Math.PI*(3-Math.sqrt(5)),ht=function(t){var e,n=1,i=.001,r=1-Math.pow(i,1/300),o=0,a=.6,s=new Map,l=it(u),c=q("tick","end");function u(){h(),c.call("tick",e),n<i&&(l.stop(),c.call("end",e))}function h(i){var l,c,u=t.length;void 0===i&&(i=1);for(var h=0;h<i;++h)for(n+=(o-n)*r,s.forEach(function(t){t(n)}),l=0;l<u;++l)null==(c=t[l]).fx?c.x+=c.vx*=a:(c.x=c.fx,c.vx=0),null==c.fy?c.y+=c.vy*=a:(c.y=c.fy,c.vy=0);return e}function d(){for(var e,n=0,i=t.length;n<i;++n){if((e=t[n]).index=n,null!=e.fx&&(e.x=e.fx),null!=e.fy&&(e.y=e.fy),isNaN(e.x)||isNaN(e.y)){var r=ct*Math.sqrt(n),o=n*ut;e.x=r*Math.cos(o),e.y=r*Math.sin(o)}(isNaN(e.vx)||isNaN(e.vy))&&(e.vx=e.vy=0)}}function f(e){return e.initialize&&e.initialize(t),e}return null==t&&(t=[]),d(),e={tick:h,restart:function(){return l.restart(u),e},stop:function(){return l.stop(),e},nodes:function(n){return arguments.length?(t=n,d(),s.forEach(f),e):t},alpha:function(t){return arguments.length?(n=+t,e):n},alphaMin:function(t){return arguments.length?(i=+t,e):i},alphaDecay:function(t){return arguments.length?(r=+t,e):+r},alphaTarget:function(t){return arguments.length?(o=+t,e):o},velocityDecay:function(t){return arguments.length?(a=1-t,e):1-a},force:function(t,n){return arguments.length>1?(null==n?s.delete(t):s.set(t,f(n)),e):s.get(t)},find:function(e,n,i){var r,o,a,s,l,c=0,u=t.length;for(null==i?i=1/0:i*=i,c=0;c<u;++c)(a=(r=e-(s=t[c]).x)*r+(o=n-s.y)*o)<i&&(l=s,i=a);return l},on:function(t,n){return arguments.length>1?(c.on(t,n),e):c.on(t)}}},dt=function(t){var e,n,i,r=P(.1);function o(t){for(var r,o=0,a=e.length;o<a;++o)(r=e[o]).vx+=(i[o]-r.x)*n[o]*t}function a(){if(e){var o,a=e.length;for(n=new Array(a),i=new Array(a),o=0;o<a;++o)n[o]=isNaN(i[o]=+t(e[o],o,e))?0:+r(e[o],o,e)}}return"function"!=typeof t&&(t=P(null==t?0:+t)),o.initialize=function(t){e=t,a()},o.strength=function(t){return arguments.length?(r="function"==typeof t?t:P(+t),a(),o):r},o.x=function(e){return arguments.length?(t="function"==typeof e?e:P(+e),a(),o):t},o},ft=function(t){var e,n,i,r=P(.1);function o(t){for(var r,o=0,a=e.length;o<a;++o)(r=e[o]).vy+=(i[o]-r.y)*n[o]*t}function a(){if(e){var o,a=e.length;for(n=new Array(a),i=new Array(a),o=0;o<a;++o)n[o]=isNaN(i[o]=+t(e[o],o,e))?0:+r(e[o],o,e)}}return"function"!=typeof t&&(t=P(null==t?0:+t)),o.initialize=function(t){e=t,a()},o.strength=function(t){return arguments.length?(r="function"==typeof t?t:P(+t),a(),o):r},o.y=function(e){return arguments.length?(t="function"==typeof e?e:P(+e),a(),o):t},o},pt=n("Gg2j"),yt=n("v9UT"),vt=n("hJ5i"),gt=n("tjMS"),mt=n("qCRI"),xt=n("CnhP"),bt=function(t){function e(){var e=t.call(this)||this;return e.childrenInited=!1,e.className="ForceDirectedSeriesDataItem",e.hasChildren.children=!0,e.childLinks=new d.b,e.applyTheme(),e}return r.c(e,t),e.prototype.show=function(t,e,n){this._visible=!0,this.node&&(this.node.isActive=!0)},e.prototype.dispatchVisibility=function(t){if(this.events.isEnabled("visibilitychanged")){var e={type:"visibilitychanged",target:this,visible:t};this.events.dispatchImmediately("visibilitychanged",e)}},e.prototype.hide=function(t,e,n,i){if(this._visible=!1,this.events.isEnabled("visibilitychanged")){var r={type:"visibilitychanged",target:this,visible:!1};this.events.dispatchImmediately("visibilitychanged",r)}this.node&&(this.node.isActive=!1)},Object.defineProperty(e.prototype,"value",{get:function(){var t=this.values.value.value;return v.isNumber(t)||this.children&&(t=0,this.children.each(function(e){t+=e.value})),t},set:function(t){this.setValue("value",t)},enumerable:!0,configurable:!0}),Object.defineProperty(e.prototype,"node",{get:function(){var t=this;if(!this._node){var e=this.component,n=e.nodes.create();n.draggable=!0,this._node=n,this._disposers.push(n),this._disposers.push(new x.b(function(){e.nodes.removeValue(n)})),this.addSprite(n),n.visible=this.visible,n.hiddenState.properties.visible=!0,e.itemsFocusable()?(n.role="menuitem",n.focusable=!0):(n.role="listitem",n.focusable=!1),n.focusable&&(n.events.once("focus",function(i){n.readerTitle=e.populateString(e.itemReaderText,t)},void 0,!1),n.events.once("blur",function(t){n.readerTitle=""},void 0,!1)),n.hoverable&&(n.events.once("over",function(i){n.readerTitle=e.populateString(e.itemReaderText,t)},void 0,!1),n.events.once("out",function(t){n.readerTitle=""},void 0,!1))}return this._node},enumerable:!0,configurable:!0}),Object.defineProperty(e.prototype,"level",{get:function(){return this.parent?this.parent.level+1:0},enumerable:!0,configurable:!0}),Object.defineProperty(e.prototype,"color",{get:function(){var t=this.properties.color;return void 0==t&&this.parent&&(t=this.parent.color),void 0==t&&this.component&&(t=this.component.colors.getIndex(this.component.colors.step*this.index)),t},set:function(t){this.setProperty("color",t)},enumerable:!0,configurable:!0}),Object.defineProperty(e.prototype,"linkWith",{get:function(){return this.properties.linkWith},set:function(t){this.setProperty("linkWith",t)},enumerable:!0,configurable:!0}),Object.defineProperty(e.prototype,"hiddenInLegend",{get:function(){return this.properties.hiddenInLegend},set:function(t){this.setProperty("hiddenInLegend",t)},enumerable:!0,configurable:!0}),Object.defineProperty(e.prototype,"collapsed",{get:function(){return this.properties.collapsed},set:function(t){this.setProperty("collapsed",t),this.node.isActive=!t},enumerable:!0,configurable:!0}),Object.defineProperty(e.prototype,"children",{get:function(){return this.properties.children},set:function(t){this.setProperty("children",t)},enumerable:!0,configurable:!0}),e.prototype.createLegendMarker=function(t){this.component.createLegendMarker(t,this),this.node.isActive||this.hide()},Object.defineProperty(e.prototype,"legendDataItem",{get:function(){return this._legendDataItem},set:function(t){this._legendDataItem=t,t.label&&(t.label.dataItem=this),t.valueLabel&&(t.valueLabel.dataItem=this)},enumerable:!0,configurable:!0}),e}(h.b),_t=function(t){function e(){var e=t.call(this)||this;return e.className="ForceDirectedSeries",e.d3forceSimulation=ht(),e.maxRadius=Object(gt.c)(8),e.minRadius=Object(gt.c)(1),e.width=Object(gt.c)(100),e.height=Object(gt.c)(100),e.colors=new b.a,e.colors.step=2,e.width=Object(gt.c)(100),e.height=Object(gt.c)(100),e.manyBodyStrength=-15,e.centerStrength=.8,e.events.on("maxsizechanged",function(){e.updateRadiuses(e.dataItems),e.updateLinksAndNodes();var t=e.d3forceSimulation;t&&(t.force("x",dt().x(e.innerWidth/2).strength(100*e.centerStrength/e.innerWidth)),t.force("y",ft().y(e.innerHeight/2).strength(100*e.centerStrength/e.innerHeight)),t.alpha()<.4&&(t.alpha(.4),t.restart()))}),e.applyTheme(),e}return r.c(e,t),e.prototype.getMaxValue=function(t,e){var n=this;return t.each(function(t){if(t.value>e&&(e=t.value),t.children){var i=n.getMaxValue(t.children,e);i>e&&(e=i)}}),e},e.prototype.validateDataItems=function(){var e=this;this._dataDisposers.push(new d.c(this.links)),this._maxValue=this.getMaxValue(this.dataItems,0),this.forceLinks=[],this.colors.reset();var n=0,i=Math.min(this.innerHeight/3,this.innerWidth/3);this.dataItems.length<=1&&(i=0),this.dataItems.each(function(t){var r=n/e.dataItems.length*360;t.node.x=e.innerWidth/2+i*pt.cos(r),t.node.y=e.innerHeight/2+i*pt.sin(r),t.node.fill=t.color,t.node.stroke=t.color,n++,e.initNode(t)}),this.dataFields.linkWith&&this.dataItems.each(function(t){e.processLinkWith(t)});var r=this.d3forceSimulation;r.on("tick",function(){e.updateLinksAndNodes()});for(var o=0;o<10;o++)r.tick();r.alphaDecay(1-Math.pow(.001,1/600)),this.chart.feedLegend(),t.prototype.validateDataItems.call(this)},e.prototype.updateNodeList=function(){var t=this.d3forceSimulation;t.nodes(this.nodes.values),this._linkForce=function(t){var e,n,i,r,o,a=T,s=function(t){return 1/Math.min(r[t.source.index],r[t.target.index])},l=P(30),c=1;function u(i){for(var r=0,a=t.length;r<c;++r)for(var s,l,u,h,d,f,p,y=0;y<a;++y)l=(s=t[y]).source,h=(u=s.target).x+u.vx-l.x-l.vx||D(),d=u.y+u.vy-l.y-l.vy||D(),h*=f=((f=Math.sqrt(h*h+d*d))-n[y])/f*i*e[y],d*=f,u.vx-=h*(p=o[y]),u.vy-=d*p,l.vx+=h*(p=1-p),l.vy+=d*p}function h(){if(i){var s,l,c=i.length,u=t.length,h=new Map(i.map(function(t,e){return[a(t,e,i),t]}));for(s=0,r=new Array(c);s<u;++s)(l=t[s]).index=s,"object"!==M(l.source)&&(l.source=V(h,l.source)),"object"!==M(l.target)&&(l.target=V(h,l.target)),r[l.source.index]=(r[l.source.index]||0)+1,r[l.target.index]=(r[l.target.index]||0)+1;for(s=0,o=new Array(u);s<u;++s)l=t[s],o[s]=r[l.source.index]/(r[l.source.index]+r[l.target.index]);e=new Array(u),d(),n=new Array(u),f()}}function d(){if(i)for(var n=0,r=t.length;n<r;++n)e[n]=+s(t[n],n,t)}function f(){if(i)for(var e=0,r=t.length;e<r;++e)n[e]=+l(t[e],e,t)}return null==t&&(t=[]),u.initialize=function(t){i=t,h()},u.links=function(e){return arguments.length?(t=e,h(),u):t},u.id=function(t){return arguments.length?(a=t,u):a},u.iterations=function(t){return arguments.length?(c=+t,u):c},u.strength=function(t){return arguments.length?(s="function"==typeof t?t:P(+t),d(),u):s},u.distance=function(t){return arguments.length?(l="function"==typeof t?t:P(+t),f(),u):l},u}(this.forceLinks),t.force("link",this._linkForce),this._collisionForce=function(t){var e,n,i=1,r=1;function o(){for(var t,o,s,l,c,u,h,d=e.length,f=0;f<r;++f)for(o=I(e,L,F).visitAfter(a),t=0;t<d;++t)s=e[t],u=n[s.index],h=u*u,l=s.x+s.vx,c=s.y+s.vy,o.visit(p);function p(t,e,n,r,o){var a=t.data,d=t.r,f=u+d;if(!a)return e>l+f||r<l-f||n>c+f||o<c-f;if(a.index>s.index){var p=l-a.x-a.vx,y=c-a.y-a.vy,v=p*p+y*y;v<f*f&&(0===p&&(v+=(p=D())*p),0===y&&(v+=(y=D())*y),v=(f-(v=Math.sqrt(v)))/v*i,s.vx+=(p*=v)*(f=(d*=d)/(h+d)),s.vy+=(y*=v)*f,a.vx-=p*(f=1-f),a.vy-=y*f)}}}function a(t){if(t.data)return t.r=n[t.data.index];for(var e=t.r=0;e<4;++e)t[e]&&t[e].r>t.r&&(t.r=t[e].r)}function s(){if(e){var i,r,o=e.length;for(n=new Array(o),i=0;i<o;++i)r=e[i],n[r.index]=+t(r,i,e)}}return"function"!=typeof t&&(t=P(null==t?1:+t)),o.initialize=function(t){e=t,s()},o.iterations=function(t){return arguments.length?(r=+t,o):r},o.strength=function(t){return arguments.length?(i=+t,o):i},o.radius=function(e){return arguments.length?(t="function"==typeof e?e:P(+e),s(),o):t},o}(),t.force("collision",this._collisionForce),t.force("x",dt().x(this.innerWidth/2).strength(100*this.centerStrength/this.innerWidth)),t.force("y",ft().y(this.innerHeight/2).strength(100*this.centerStrength/this.innerHeight))},e.prototype.updateLinksAndNodes=function(){var t=this;this._linkForce&&(this._linkForce.distance(function(e){return t.getDistance(e)}),this._linkForce.strength(function(e){return t.getStrength(e)})),this._collisionForce&&this._collisionForce.radius(function(t){if(t instanceof m){var e=t.circle.pixelRadius;return t.outerCircle.__disabled||t.outerCircle.disabled||!t.outerCircle.visible||(e=(e+3)*t.outerCircle.scale),e}return 1}),this.d3forceSimulation.force("manybody",function(){var t,e,n,i,r=P(-30),o=1,a=1/0,s=.81;function l(i){var r,o=t.length,a=I(t,st,lt).visitAfter(u);for(n=i,r=0;r<o;++r)e=t[r],a.visit(h)}function c(){if(t){var e,n,o=t.length;for(i=new Array(o),e=0;e<o;++e)n=t[e],i[n.index]=+r(n,e,t)}}function u(t){var e,n,r,o,a,s=0,l=0;if(t.length){for(r=o=a=0;a<4;++a)(e=t[a])&&(n=Math.abs(e.value))&&(s+=e.value,l+=n,r+=n*e.x,o+=n*e.y);t.x=r/l,t.y=o/l}else{(e=t).x=e.data.x,e.y=e.data.y;do{s+=i[e.data.index]}while(e=e.next)}t.value=s}function h(t,r,l,c){if(!t.value)return!0;var u=t.x-e.x,h=t.y-e.y,d=c-r,f=u*u+h*h;if(d*d/s<f)return f<a&&(0===u&&(f+=(u=D())*u),0===h&&(f+=(h=D())*h),f<o&&(f=Math.sqrt(o*f)),e.vx+=u*t.value*n/f,e.vy+=h*t.value*n/f),!0;if(!(t.length||f>=a)){(t.data!==e||t.next)&&(0===u&&(f+=(u=D())*u),0===h&&(f+=(h=D())*h),f<o&&(f=Math.sqrt(o*f)));do{t.data!==e&&(d=i[t.data.index]*n/f,e.vx+=u*d,e.vy+=h*d)}while(t=t.next)}}return l.initialize=function(e){t=e,c()},l.strength=function(t){return arguments.length?(r="function"==typeof t?t:P(+t),c(),l):r},l.distanceMin=function(t){return arguments.length?(o=t*t,l):Math.sqrt(o)},l.distanceMax=function(t){return arguments.length?(a=t*t,l):Math.sqrt(a)},l.theta=function(t){return arguments.length?(s=t*t,l):Math.sqrt(s)},l}().strength(function(e){return e instanceof m?e.circle.pixelRadius*t.manyBodyStrength:t.manyBodyStrength}))},e.prototype.getDistance=function(t){var e=t.source,n=t.target,i=0;if(n.dataItem&&e.dataItem){var r=e.linksWith.getKey(n.uid);return r&&(i=r.distance),e.isActive||(i=1),i*(e.circle.pixelRadius+n.circle.pixelRadius)}return i},e.prototype.getStrength=function(t){var e=t.source,n=t.target,i=0,r=e.linksWith.getKey(n.uid);return r&&(i=r.strength),i},e.prototype.nodeDragEnded=function(){this.d3forceSimulation.alphaTarget(0)},e.prototype.nodeDragStarted=function(){this.d3forceSimulation.alpha(.1),this.d3forceSimulation.restart()},e.prototype.restartSimulation=function(){this.d3forceSimulation.alpha()<=.3&&(this.d3forceSimulation.alpha(.3),this.d3forceSimulation.restart())},e.prototype.updateRadiuses=function(t){var e=this;t.each(function(t){e.updateRadius(t),t.childrenInited&&e.updateRadiuses(t.children)})},e.prototype.updateRadius=function(t){var e=t.node,n=(this.innerWidth+this.innerHeight)/2,i=yt.relativeToValue(this.minRadius,n),r=yt.relativeToValue(this.maxRadius,n),o=i+t.value/this._maxValue*(r-i);v.isNumber(o)||(o=i),e.circle.radius=o,e.outerCircle.radius=o+3,e.circle.states.getKey("active").properties.radius=o,e.circle.defaultState.properties.radius=o},e.prototype.initNode=function(t){var e=this,n=t.node;if(n.parent=this,this.updateRadius(t),t.children&&0!=t.children.length?n.cursorOverStyle=mt.a.pointer:(n.outerCircle.disabled=!0,n.circle.interactionsEnabled=!0,n.cursorOverStyle=mt.a.default),this.dataItemsInvalid&&(t.level>=this.maxLevels-1||t.collapsed))return n.isActive=!1,void this.updateNodeList();if(n.isActive||n.hide(0),t.children){var i=0;t.childrenInited=!0,1==this.dataItems.length&&0==t.level&&this.colors.next(),t.children.each(function(r){var o=n.linkWith(r.node);r.parentLink=o;var a,s=2*n.circle.pixelRadius+r.node.circle.pixelRadius,l=i/t.children.length*360;r.node.x=n.pixelX+s*pt.cos(l),r.node.y=n.pixelY+s*pt.sin(l),r.node.circle.radius=0;var c=r.properties.color;a=v.hasValue(c)?c:1==e.dataItems.length&&0==t.level?e.colors.next():t.color,r.color=a,r.node.fill=a,r.node.stroke=a,r.parentLink.stroke=a,e.initNode(r),i++})}n.isActive=!0,n.show(),this.updateNodeList()},e.prototype.processLinkWith=function(t){var e=this;t.linkWith&&vt.each(t.linkWith,function(n,i){var r=e.getDataItemById(e.dataItems,n);r&&t.node.linkWith(r.node,e.linkWithStrength)}),t.children&&t.children.each(function(t){e.processLinkWith(t)})},e.prototype.getDataItemById=function(t,e){for(var n=t.length-1;n>=0;n--){var i=t.getIndex(n);if(i.id==e)return i;if(i.children){var r=this.getDataItemById(i.children,e);if(r)return r}}},e.prototype.createDataItem=function(){return new bt},Object.defineProperty(e.prototype,"nodes",{get:function(){if(!this._nodes){var t=this.createNode();t.applyOnClones=!0,this._disposers.push(t),this._nodes=new d.e(t),this._disposers.push(new d.c(this._nodes))}return this._nodes},enumerable:!0,configurable:!0}),Object.defineProperty(e.prototype,"links",{get:function(){if(!this._links){var t=this.createLink();t.applyOnClones=!0,this._disposers.push(t),this._links=new d.e(t),this._disposers.push(new d.c(this._links))}return this._links},enumerable:!0,configurable:!0}),e.prototype.createNode=function(){return new m},e.prototype.createLink=function(){return new c},Object.defineProperty(e.prototype,"minRadius",{get:function(){return this.getPropertyValue("minRadius")},set:function(t){this.setPropertyValue("minRadius",t,!0)},enumerable:!0,configurable:!0}),Object.defineProperty(e.prototype,"maxRadius",{get:function(){return this.getPropertyValue("maxRadius")},set:function(t){this.setPropertyValue("maxRadius",t,!0)},enumerable:!0,configurable:!0}),Object.defineProperty(e.prototype,"colors",{get:function(){return this.getPropertyValue("colors")},set:function(t){this.setPropertyValue("colors",t,!0)},enumerable:!0,configurable:!0}),Object.defineProperty(e.prototype,"maxLevels",{get:function(){return this.getPropertyValue("maxLevels")},set:function(t){this.setPropertyValue("maxLevels",t,!0)},enumerable:!0,configurable:!0}),Object.defineProperty(e.prototype,"manyBodyStrength",{get:function(){return this.getPropertyValue("manyBodyStrength")},set:function(t){this.setPropertyValue("manyBodyStrength",t)&&this.restartSimulation()},enumerable:!0,configurable:!0}),Object.defineProperty(e.prototype,"centerStrength",{get:function(){return this.getPropertyValue("centerStrength")},set:function(t){this.setPropertyValue("centerStrength",t)&&this.restartSimulation()},enumerable:!0,configurable:!0}),Object.defineProperty(e.prototype,"linkWithStrength",{get:function(){return this.getPropertyValue("linkWithStrength")},set:function(t){this.setPropertyValue("linkWithStrength",t)&&this.restartSimulation()},enumerable:!0,configurable:!0}),e.prototype.createLegendMarker=function(t,e){t.children.each(function(n){var i=e.node;n instanceof xt.a&&n.cornerRadius(40,40,40,40),n.defaultState.properties.fill=i.fill,n.defaultState.properties.stroke=i.stroke,n.defaultState.properties.fillOpacity=i.fillOpacity,n.defaultState.properties.strokeOpacity=i.strokeOpacity,n.fill=i.fill,n.stroke=i.stroke,n.fillOpacity=i.fillOpacity,n.strokeOpacity=i.strokeOpacity,void 0==n.fill&&(n.__disabled=!0);var r=t.dataItem;r.color=i.fill,r.colorOrig=i.fill,i.events.on("propertychanged",function(t){"fill"==t.property&&(n.__disabled=!1,n.isActive||(n.fill=i.fill),n.defaultState.properties.fill=i.fill,r.color=i.fill,r.colorOrig=i.fill),"stroke"==t.property&&(n.isActive||(n.stroke=i.stroke),n.defaultState.properties.stroke=i.stroke)},void 0,!1)})},e}(h.a);a.b.registeredClasses.ForceDirectedSeries=_t,a.b.registeredClasses.ForceDirectedSeriesDataItem=bt;var wt=function(t){function e(){return null!==t&&t.apply(this,arguments)||this}return r.c(e,t),e}(u.b),kt=function(t){function e(){var e=t.call(this)||this;return e.className="ForceDirectedTree",e.seriesContainer.isMeasured=!0,e.seriesContainer.layout="absolute",e.applyTheme(),e}return r.c(e,t),e.prototype.createSeries=function(){return new _t},e.prototype.createDataItem=function(){return new wt},e.prototype.feedLegend=function(){var t=this.legend;if(t){var e=[];this.series.each(function(n){if(!n.hiddenInLegend){var i=n.dataItems;if(1==i.length){var r=n.dataItems.getIndex(0).children;r.length>0&&(i=r)}i.each(function(i){if(!i.hiddenInLegend){e.push(i);var r=n.legendSettings;r&&(r.labelText&&(t.labels.template.text=r.labelText),r.itemLabelText&&(t.labels.template.text=r.itemLabelText),r.valueText&&(t.valueLabels.template.text=r.valueText),r.itemValueText&&(t.valueLabels.template.text=r.itemValueText))}})}}),t.data=e,t.dataFields.name="name"}},e.prototype.applyInternalDefaults=function(){t.prototype.applyInternalDefaults.call(this),v.hasValue(this.readerTitle)||(this.readerTitle=this.language.translate("Force directed tree"))},e.prototype.getExporting=function(){var e=this,n=t.prototype.getExporting.call(this);return n.adapter.add("formatDataFields",function(t){return"csv"!=t.format&&"xlsx"!=t.format||e.series.each(function(e){v.hasValue(e.dataFields.children)&&delete t.dataFields[e.dataFields.children]}),t}),n},e}(u.a);a.b.registeredClasses.ForceDirectedTree=kt,a.b.registeredClasses.ForceDirectedTreeDataItem=wt,window.am4plugins_forceDirected=i}},["eUZ+"]);
//#  =forceDirected.js.map