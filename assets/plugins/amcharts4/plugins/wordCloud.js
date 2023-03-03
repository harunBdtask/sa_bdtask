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
am4internal_webpackJsonp(["4859"],{"6JTK":function(e,t,i){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var r={};i.d(r,"WordCloudDataItem",function(){return _}),i.d(r,"WordCloud",function(){return C}),i.d(r,"WordCloudSeriesDataItem",function(){return v}),i.d(r,"WordCloudSeries",function(){return x});var a=i("m4/l"),n=i("2I/e"),o=i("aM7D"),s=i("Vs7R"),l=i("C6dT"),u=i("p9TX"),h=i("vMqJ"),p=i("8ZqG"),c=i("aCit"),d=i("hGwe"),m=i("v9UT"),f=i("Gg2j"),g=i("58Sn"),y=i("tjMS"),b=i("hD5A"),v=function(e){function t(){var t=e.call(this)||this;return t.className="WordCloudSeriesDataItem",t.applyTheme(),t}return a.c(t,e),t.prototype.hide=function(t,i,r,a){return a||(a=["value"]),e.prototype.hide.call(this,t,i,0,a)},t.prototype.setVisibility=function(t,i){i||(t?this.setWorkingValue("value",this.values.value.value,0,0):this.setWorkingValue("value",0,0,0)),e.prototype.setVisibility.call(this,t,i)},t.prototype.show=function(t,i,r){return r||(r=["value"]),e.prototype.show.call(this,t,i,r)},Object.defineProperty(t.prototype,"word",{get:function(){return this.properties.word},set:function(e){this.setProperty("word",e)},enumerable:!0,configurable:!0}),Object.defineProperty(t.prototype,"label",{get:function(){var e=this;if(!this._label){var t=this.component.labels.create();this._label=t,this._disposers.push(t),t.parent=this.component.labelsContainer,t.isMeasured=!1,t.x=Object(y.c)(50),t.y=Object(y.c)(50),t.fontSize=0,this.component.colors&&(t.fill=this.component.colors.next()),this._disposers.push(new b.b(function(){e.component&&e.component.labels.removeValue(t)})),this.addSprite(t),t.visible=this.visible}return this._label},enumerable:!0,configurable:!0}),t}(o.b),x=function(e){function t(){var t=e.call(this)||this;t._adjustedFont=1,t.className="WordCloudSeries",t.accuracy=5,t.isMeasured=!0,t.minFontSize=Object(y.c)(2),t.maxFontSize=Object(y.c)(20),t.excludeWords=[],t.layout="absolute",t.angles=[0,0,90],t.rotationThreshold=.7,t.minWordLength=1,t.width=Object(y.c)(100),t.height=Object(y.c)(100),t.step=15,t.randomness=.2,t.labels.template.horizontalCenter="middle",t.labels.template.verticalCenter="middle",t.itemReaderText="{word}: {value}",t.applyTheme();var i=document.createElement("canvas");i.style.position="absolute",i.style.top="0px",i.style.left="0px",i.style.opacity="0.5",t._canvas=i,t._ctx=i.getContext("2d"),t._maskSprite=t.createChild(s.a);var r=t.createChild(l.a);return r.shouldClone=!1,r.isMeasured=!1,r.layout="none",t.labelsContainer=r,t._spiral=r.createChild(s.a),t._spiral.fillOpacity=.1,t._spiral.strokeOpacity=1,t._spiral.stroke=Object(p.c)("#000"),t}return a.c(t,e),t.prototype.validateDataRange=function(){e.prototype.validateDataRange.call(this),this.dataItems.each(function(e){m.used(e.label)})},t.prototype.validate=function(){e.prototype.validate.call(this),this._currentIndex=0,this.dataItems.values.sort(function(e,t){return e.value==t.value?0:e.value>t.value?-1:1}),this._processTimeout&&this._processTimeout.dispose();var t=this.innerWidth,i=this.innerHeight;if(t>0&&i>0){var r=this._ctx;this._canvas.width=t,this._canvas.height=i,r.fillStyle="white",r.fillRect(0,0,t,i),this._points=d.spiralPoints(0,0,t,i,0,this.step,this.step);for(var a=this.labelsContainer.rotation,n=this._points.length-1;n>=0;n--){var o=this._points[n];if(o.x<-t/2||o.x>t/2||o.y<-i/2||o.y>i/2)this._points.splice(n,1);else if(0!=a){var s=m.spritePointToSprite({x:o.x+t/2,y:o.y+i/2},this,this.labelsContainer);o.x=s.x,o.y=s.y}}var l=this._maskSprite;if(l.path){var u=this.maxWidth,h=this.maxHeight;l.isMeasured=!0,l.validate();var p=l.measuredWidth/l.scale,c=l.measuredHeight/l.scale,g=f.min(h/c,u/p);g==1/0&&(g=1),l.horizontalCenter="none",l.verticalCenter="none",l.x=0,l.y=0,l.scale=1,g=f.max(.001,g),l.horizontalCenter="middle",l.verticalCenter="middle",l.x=t/2,l.y=i/2,l.validatePosition(),this.mask=l,l.scale=g}this.events.isEnabled("arrangestarted")&&this.dispatchImmediately("arrangestarted"),this.processItem(this.dataItems.getIndex(this._currentIndex))}},t.prototype.processItem=function(e){var t=this,i=this._ctx,r=this.innerWidth,a=this.innerHeight;if(g.s(this.htmlContainer))return this._processTimeout=this.setTimeout(function(){t._currentIndex++,t.processItem(t.dataItems.getIndex(t._currentIndex))},500),void this._disposers.push(this._processTimeout);this.labelsContainer.x=r/2,this.labelsContainer.y=a/2;var n=e.label,o=g.l(n.element.node),s=f.min(this.innerHeight,this.innerWidth),l=m.relativeToValue(this.minFontSize,s),u=m.relativeToValue(this.maxFontSize,s),h=this.dataItem.values.value.low,p=this.dataItem.values.value.high,c=(e.value-h)/(p-h);h==p&&(c=this.dataItems.length>1?1/this.dataItems.length*1.5:1);var d=l+(u-l)*c*this._adjustedFont,y=n.fontSize;n.fontSize=d;var b=0;if((d-l)/(u-l)<this.rotationThreshold&&(b=this.angles[Math.round(Math.random()*(this.angles.length-1))]),n.fontSize=d,n.rotation=b,n.show(0),n.hardInvalidate(),n.validate(),n.measuredWidth>.95*r||n.measuredHeight>.95*a)return this._adjustedFont-=.1,this.invalidateDataItems(),void this.invalidate();var v=n.maxLeft,x=n.maxRight,_=n.maxTop,C=n.maxBottom,P=!0,I=Math.round(Math.random()*this._points.length*this.randomness),w=n.pixelX,V=n.pixelY,W=0,S=0;if(m.used(this.labelsContainer.rotation),this._currentIndex>0)for(;P;){if(I>this._points.length-1)return P=!1,this._adjustedFont-=.1,void this.invalidateDataItems();P=!1,W=this._points[I].x,S=this._points[I].y;for(var j=n.pixelMarginLeft,T=n.pixelMarginRight,O=n.pixelMarginTop,z={x:W+v-j,y:S+_-O,width:x-v+j+T,height:C-_+O+n.pixelMarginBottom},D=this._ctx.getImageData(z.x+r/2,z.y+a/2,z.width,z.height).data,M=0;M<D.length;M+=Math.pow(2,this.accuracy))if(255!=D[M]){P=!0,n.currentText.length>3&&(0==b&&x-v<60&&this._points.splice(I,1),90==Math.abs(b)&&C-_<50&&this._points.splice(I,1));break}I+=5}0==y?(n.animate([{property:"fontSize",to:d,from:y}],this.interpolationDuration,this.interpolationEasing),n.x=W,n.y=S):n.animate([{property:"fontSize",to:d,from:y},{property:"x",to:W,from:w},{property:"y",to:S,from:V}],this.interpolationDuration,this.interpolationEasing);var F=W+r/2,L=S+a/2;i.translate(F,L);var k=n.rotation*Math.PI/180;i.rotate(k),i.textAlign="center",i.textBaseline="middle",i.fillStyle="blue";var R=(n.fontWeight||this.fontWeight||this.chart.fontWeight||"normal")+" "+d+"px "+o;if(i.font=R,i.fillText(n.currentText,0,0),i.rotate(-k),i.translate(-F,-L),n.showOnInit&&(n.hide(0),n.show()),this.events.isEnabled("arrangeprogress")){var E={type:"arrangeprogress",target:this,progress:(this._currentIndex+1)/this.dataItems.length};this.events.dispatchImmediately("arrangeprogress",E)}this._currentIndex<this.dataItems.length-1?(this._processTimeout=this.setTimeout(function(){t._currentIndex++,t.processItem(t.dataItems.getIndex(t._currentIndex))},1),this._disposers.push(this._processTimeout)):this.events.isEnabled("arrangeended")&&this.dispatchImmediately("arrangeended")},t.prototype.createLabel=function(){return new u.a},Object.defineProperty(t.prototype,"labels",{get:function(){if(!this._labels){var e=this.createLabel();e.applyOnClones=!0,this._disposers.push(e),e.text="{word}",e.margin(2,3,2,3),e.padding(0,0,0,0),this._labels=new h.e(e),this._disposers.push(new h.c(this._labels))}return this._labels},enumerable:!0,configurable:!0}),t.prototype.createDataItem=function(){return new v},Object.defineProperty(t.prototype,"colors",{get:function(){return this.getPropertyValue("colors")},set:function(e){this.setPropertyValue("colors",e,!0)},enumerable:!0,configurable:!0}),t.prototype.updateData=function(){this.data=this.getWords(this.text)},Object.defineProperty(t.prototype,"text",{get:function(){return this.getPropertyValue("text")},set:function(e){this.setPropertyValue("text",e)&&this.updateData()},enumerable:!0,configurable:!0}),Object.defineProperty(t.prototype,"maxCount",{get:function(){return this.getPropertyValue("maxCount")},set:function(e){this.setPropertyValue("maxCount",e)&&this.updateData()},enumerable:!0,configurable:!0}),Object.defineProperty(t.prototype,"minValue",{get:function(){return this.getPropertyValue("minValue")},set:function(e){this.setPropertyValue("minValue",e)&&this.updateData()},enumerable:!0,configurable:!0}),Object.defineProperty(t.prototype,"excludeWords",{get:function(){return this.getPropertyValue("excludeWords")},set:function(e){this.setPropertyValue("excludeWords",e)&&this.updateData()},enumerable:!0,configurable:!0}),Object.defineProperty(t.prototype,"maxFontSize",{get:function(){return this.getPropertyValue("maxFontSize")},set:function(e){this.setPropertyValue("maxFontSize",e,!0)},enumerable:!0,configurable:!0}),Object.defineProperty(t.prototype,"minFontSize",{get:function(){return this.getPropertyValue("minFontSize")},set:function(e){this.setPropertyValue("minFontSize",e,!0)},enumerable:!0,configurable:!0}),Object.defineProperty(t.prototype,"randomness",{get:function(){return this.getPropertyValue("randomness")},set:function(e){this.setPropertyValue("randomness",e,!0)},enumerable:!0,configurable:!0}),Object.defineProperty(t.prototype,"step",{get:function(){return this.getPropertyValue("step")},set:function(e){this.setPropertyValue("step",e,!0)},enumerable:!0,configurable:!0}),Object.defineProperty(t.prototype,"accuracy",{get:function(){return this.getPropertyValue("accuracy")},set:function(e){this.setPropertyValue("accuracy",e,!0)},enumerable:!0,configurable:!0}),Object.defineProperty(t.prototype,"minWordLength",{get:function(){return this.getPropertyValue("minWordLength")},set:function(e){this.setPropertyValue("minWordLength",e)&&this.updateData()},enumerable:!0,configurable:!0}),Object.defineProperty(t.prototype,"rotationThreshold",{get:function(){return this.getPropertyValue("rotationThreshold")},set:function(e){this.setPropertyValue("rotationThreshold",e,!0)},enumerable:!0,configurable:!0}),Object.defineProperty(t.prototype,"angles",{get:function(){return this.getPropertyValue("angles")},set:function(e){this.setPropertyValue("angles",e,!0)},enumerable:!0,configurable:!0}),Object.defineProperty(t.prototype,"maskSprite",{get:function(){return this._maskSprite},enumerable:!0,configurable:!0}),t.prototype.copyFrom=function(t){e.prototype.copyFrom.call(this,t),this.labels.template.copyFrom(t.labels.template)},t.prototype.getWords=function(e){if(e){this.dataFields.word="word",this.dataFields.value="value";var t=new RegExp("[A-Za-zªµºÀ-ÖØ-öø-ˁˆ-ˑˠ-ˤˬˮͰ-ʹͶ-ͷͺ-ͽΆΈ-ΊΌΎ-ΡΣ-ϵϷ-ҁҊ-ԣԱ-Ֆՙա-ևא-תװ-ײء-يٮ-ٯٱ-ۓەۥ-ۦۮ-ۯۺ-ۼۿܐܒ-ܯݍ-ޥޱߊ-ߪߴ-ߵߺऄ-हऽॐक़-ॡॱ-ॲॻ-ॿঅ-ঌএ-ঐও-নপ-রলশ-হঽৎড়-ঢ়য়-ৡৰ-ৱਅ-ਊਏ-ਐਓ-ਨਪ-ਰਲ-ਲ਼ਵ-ਸ਼ਸ-ਹਖ਼-ੜਫ਼ੲ-ੴઅ-ઍએ-ઑઓ-નપ-રલ-ળવ-હઽૐૠ-ૡଅ-ଌଏ-ଐଓ-ନପ-ରଲ-ଳଵ-ହଽଡ଼-ଢ଼ୟ-ୡୱஃஅ-ஊஎ-ஐஒ-கங-சஜஞ-டண-தந-பம-ஹௐఅ-ఌఎ-ఐఒ-నప-ళవ-హఽౘ-ౙౠ-ౡಅ-ಌಎ-ಐಒ-ನಪ-ಳವ-ಹಽೞೠ-ೡഅ-ഌഎ-ഐഒ-നപ-ഹഽൠ-ൡൺ-ൿඅ-ඖක-නඳ-රලව-ෆก-ะา-ำเ-ๆກ-ຂຄງ-ຈຊຍດ-ທນ-ຟມ-ຣລວສ-ຫອ-ະາ-ຳຽເ-ໄໆໜ-ໝༀཀ-ཇཉ-ཬྈ-ྋက-ဪဿၐ-ၕၚ-ၝၡၥ-ၦၮ-ၰၵ-ႁႎႠ-Ⴥა-ჺჼᄀ-ᅙᅟ-ᆢᆨ-ᇹሀ-ቈቊ-ቍቐ-ቖቘቚ-ቝበ-ኈኊ-ኍነ-ኰኲ-ኵኸ-ኾዀዂ-ዅወ-ዖዘ-ጐጒ-ጕጘ-ፚᎀ-ᎏᎠ-Ᏼᐁ-ᙬᙯ-ᙶᚁ-ᚚᚠ-ᛪᛮ-ᛰᜀ-ᜌᜎ-ᜑᜠ-ᜱᝀ-ᝑᝠ-ᝬᝮ-ᝰក-ឳៗៜᠠ-ᡷᢀ-ᢨᢪᤀ-ᤜᥐ-ᥭᥰ-ᥴᦀ-ᦩᧁ-ᧇᨀ-ᨖᬅ-ᬳᭅ-ᭋᮃ-ᮠᮮ-ᮯᰀ-ᰣᱍ-ᱏᱚ-ᱽᴀ-ᶿḀ-ἕἘ-Ἕἠ-ὅὈ-Ὅὐ-ὗὙὛὝὟ-ώᾀ-ᾴᾶ-ᾼιῂ-ῄῆ-ῌῐ-ΐῖ-Ίῠ-Ῥῲ-ῴῶ-ῼⁱⁿₐ-ₔℂℇℊ-ℓℕℙ-ℝℤΩℨK-ℭℯ-ℹℼ-ℿⅅ-ⅉⅎⅠ-ↈⰀ-Ⱞⰰ-ⱞⱠ-Ɐⱱ-ⱽⲀ-ⳤⴀ-ⴥⴰ-ⵥⵯⶀ-ⶖⶠ-ⶦⶨ-ⶮⶰ-ⶶⶸ-ⶾⷀ-ⷆⷈ-ⷎⷐ-ⷖⷘ-ⷞⸯ々-〇〡-〩〱-〵〸-〼ぁ-ゖゝ-ゟァ-ヺー-ヿㄅ-ㄭㄱ-ㆎㆠ-ㆷㇰ-ㇿ㐀䶵一鿃ꀀ-ꒌꔀ-ꘌꘐ-ꘟꘪ-ꘫꙀ-ꙟꙢ-ꙮꙿ-ꚗꜗ-ꜟꜢ-ꞈꞋ-ꞌꟻ-ꠁꠃ-ꠅꠇ-ꠊꠌ-ꠢꡀ-ꡳꢂ-ꢳꤊ-ꤥꤰ-ꥆꨀ-ꨨꩀ-ꩂꩄ-ꩋ가힣豈-鶴侮-頻並-龎ﬀ-ﬆﬓ-ﬗיִײַ-ﬨשׁ-זּטּ-לּמּנּ-סּףּ-פּצּ-ﮱﯓ-ﴽﵐ-ﶏﶒ-ﷇﷰ-ﷻﹰ-ﹴﹶ-ﻼＡ-Ｚａ-ｚｦ-ﾾￂ-ￇￊ-ￏￒ-ￗￚ-ￜ0-9@+]+","ig"),i=e.match(t);if(!i)return[];for(var r=[],a=void 0;a=i.pop();){for(var n=void 0,o=0;o<r.length;o++)if(r[o].word.toLowerCase()==a.toLowerCase()){n=r[o];break}n?(n.value++,this.isCapitalized(a)||(n.word=a)):r.push({word:a,value:1})}var s=this.excludeWords;if(this.minValue>1||this.minWordLength>1||s&&s.length>0)for(o=r.length-1;o>=0;o--){var l=r[o];l.value<this.minValue&&r.splice(o,1),l.word.length<this.minWordLength&&r.splice(o,1),-1!==s.indexOf(l.word)&&r.splice(o,1)}return r.sort(function(e,t){return e.value==t.value?0:e.value>t.value?-1:1}),r.length>this.maxCount&&(r=r.slice(0,this.maxCount)),r}},t.prototype.isCapitalized=function(e){var t=e.toLowerCase();return e[0]!=t[0]&&e.substr(1)==t.substr(1)&&e!=t},t}(o.a);c.b.registeredClasses.WordCloudSeries=x,c.b.registeredClasses.WordCloudSeriesDataItem=v;var _=function(e){function t(){var t=e.call(this)||this;return t.className="WordCloudDataItem",t.applyTheme(),t}return a.c(t,e),t}(n.b),C=function(e){function t(){var t=e.call(this)||this;return t.className="WordCloud",t.seriesContainer.isMeasured=!0,t.seriesContainer.layout="absolute",t.applyTheme(),t}return a.c(t,e),t.prototype.createSeries=function(){return new x},t}(n.a);c.b.registeredClasses.WordCloud=C,c.b.registeredClasses.WordCloudDataItem=_,window.am4plugins_wordCloud=r}},["6JTK"]);
//#  =wordCloud.js.map