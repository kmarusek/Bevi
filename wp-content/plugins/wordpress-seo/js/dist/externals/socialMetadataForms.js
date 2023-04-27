window.yoast=window.yoast||{},window.yoast.socialMetadataForms=function(e){var t={};function i(n){if(t[n])return t[n].exports;var o=t[n]={i:n,l:!1,exports:{}};return e[n].call(o.exports,o,o.exports,i),o.l=!0,o.exports}return i.m=e,i.c=t,i.d=function(e,t,n){i.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:n})},i.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},i.t=function(e,t){if(1&t&&(e=i(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var n=Object.create(null);if(i.r(n),Object.defineProperty(n,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var o in e)i.d(n,o,function(t){return e[t]}.bind(null,o));return n},i.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return i.d(t,"a",t),t},i.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},i.p="",i(i.s=377)}({0:function(e,t){e.exports=window.yoast.propTypes},1:function(e,t){e.exports=window.wp.element},16:function(e,t){e.exports=window.yoast.replacementVariableEditor},19:function(e,t){e.exports=window.yoast.redux},2:function(e,t){e.exports=window.lodash},3:function(e,t){e.exports=window.React},377:function(e,t,i){"use strict";i.r(t),i.d(t,"FACEBOOK_IMAGE_SIZES",(function(){return I})),i.d(t,"TWITTER_IMAGE_SIZES",(function(){return v})),i.d(t,"determineFacebookImageMode",(function(){return w})),i.d(t,"socialReducer",(function(){return R})),i.d(t,"SocialMetadataPreviewForm",(function(){return $})),i.d(t,"actions",(function(){return n})),i.d(t,"selectorsFactory",(function(){return S}));var n={};i.r(n),i.d(n,"SET_SOCIAL_TITLE",(function(){return o})),i.d(n,"SET_SOCIAL_DESCRIPTION",(function(){return r})),i.d(n,"SET_SOCIAL_IMAGE_URL",(function(){return a})),i.d(n,"SET_SOCIAL_IMAGE_TYPE",(function(){return c})),i.d(n,"SET_SOCIAL_IMAGE_ID",(function(){return s})),i.d(n,"SET_SOCIAL_IMAGE",(function(){return l})),i.d(n,"CLEAR_SOCIAL_IMAGE",(function(){return d})),i.d(n,"setSocialPreviewTitle",(function(){return u})),i.d(n,"setSocialPreviewDescription",(function(){return p})),i.d(n,"setSocialPreviewImageUrl",(function(){return g})),i.d(n,"setSocialPreviewImageType",(function(){return m})),i.d(n,"setSocialPreviewImageId",(function(){return h})),i.d(n,"setSocialPreviewImage",(function(){return f})),i.d(n,"clearSocialPreviewImage",(function(){return b}));const o="SET_SOCIAL_TITLE",r="SET_SOCIAL_DESCRIPTION",a="SET_SOCIAL_IMAGE_URL",c="SET_SOCIAL_IMAGE_TYPE",s="SET_SOCIAL_IMAGE_ID",l="SET_SOCIAL_IMAGE",d="CLEAR_SOCIAL_IMAGE",u=(e,t)=>({type:o,platform:t,title:e}),p=(e,t)=>({type:r,platform:t,description:e}),g=(e,t)=>({type:a,platform:t,imageUrl:e}),m=(e,t)=>({type:c,platform:t,imageType:e}),h=(e,t)=>({type:s,platform:t,imageId:e}),f=(e,t)=>({type:l,platform:t,image:e}),b=e=>({type:d,platform:e});var E=i(2),S=e=>{const t={getFacebookData:t=>Object(E.get)(t,e+".facebook",{}),getFacebookTitle:e=>t.getFacebookData(e).title,getFacebookDescription:e=>t.getFacebookData(e).description,getFacebookImageUrl:e=>t.getFacebookData(e).image.url,getFacebookImageType:e=>t.getFacebookData(e).image.type,getTwitterData:t=>Object(E.get)(t,e+".twitter",{}),getTwitterTitle:e=>t.getTwitterData(e).title,getTwitterDescription:e=>t.getTwitterData(e).description,getTwitterImageUrl:e=>t.getTwitterData(e).image.url,getTwitterImageType:e=>t.getTwitterData(e).image.type};return t};const v={squareWidth:125,squareHeight:125,landscapeWidth:506,landscapeHeight:265,aspectRatio:50.2},I={squareWidth:158,squareHeight:158,landscapeWidth:527,landscapeHeight:273,portraitWidth:158,portraitHeight:237,aspectRatio:52.2,largeThreshold:{width:446,height:233}};var w=function(e){const{largeThreshold:t}=I;return e.height>e.width?"portrait":e.width<t.width||e.height<t.height||e.height===e.width?"square":"landscape"},T=i(19);const y={title:"",description:"",warnings:[],image:{bytes:null,type:null,height:null,width:null,url:"",id:null,alt:""}};function _(){let e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:y,t=arguments.length>1?arguments[1]:void 0;switch(t.type){case o:return{...e,title:t.title};case r:return{...e,description:t.description};case l:return{...e,image:{...t.image}};case a:return{...e,image:{...e.image,url:t.imageUrl}};case c:return{...e,image:{...e.image,type:t.imageType}};case s:return{...e,image:{...e.image,id:t.imageId}};case d:return{...e,image:{bytes:null,type:null,height:null,width:null,url:"",id:null,alt:""}};default:return e}}function O(e,t){return(i,n)=>{const{platform:o}=n;return void 0===i?y:o!==t?i:e(i,n)}}var R=Object(T.combineReducers)({facebook:O(_,"facebook"),twitter:O(_,"twitter")}),A=i(1),C=i(4),j=i(9),M=i(16),x=i(6),D=i(0),L=i.n(D),P=i(3),k=i(5),F=i.n(k),H=i(8);const V=e=>e?x.colors.$color_snippet_focus:x.colors.$color_snippet_hover,q=F.a.div`
	position: relative;`,G=F.a.div`
	display: ${e=>e.isActive||e.isHovered?"block":"none"};

	::before {
		position: absolute;
		top: -2px;
		${Object(j.getDirectionalStyle)("left","right")}: -25px;
		width: 24px;
		height: 24px;
		background-image: url(
		${e=>Object(j.getDirectionalStyle)(Object(x.angleRight)(V(e.isActive)),Object(x.angleLeft)(V(e.isActive)))}
		);
		color: ${e=>V(e.isActive)};
		background-size: 24px;
		background-repeat: no-repeat;
		background-position: center;
		content: "";
	}
`;G.propTypes={isActive:L.a.bool,isHovered:L.a.bool},G.defaultProps={isActive:!1,isHovered:!1};const U=(W=H.ImageSelect,function e(t){e.propTypes={isActive:L.a.bool.isRequired,isHovered:L.a.bool.isRequired};const{isActive:i,isHovered:n,...o}=t;return Object(A.createElement)(q,null,Object(A.createElement)(G,{isActive:i,isHovered:n}),Object(A.createElement)(W,o))});var W;class B extends P.Component{constructor(e){super(e),this.onImageEnter=e.onMouseHover.bind(this,"image"),this.onTitleEnter=e.onMouseHover.bind(this,"title"),this.onDescriptionEnter=e.onMouseHover.bind(this,"description"),this.onLeave=e.onMouseHover.bind(this,""),this.onImageSelectBlur=e.onSelect.bind(this,""),this.onSelectTitleEditor=this.onSelectEditor.bind(this,"title"),this.onSelectDescriptionEditor=this.onSelectEditor.bind(this,"description"),this.onDeselectEditor=this.onSelectEditor.bind(this,""),this.onTitleEditorRef=this.onSetEditorRef.bind(this,"title"),this.onDescriptionEditorRef=this.onSetEditorRef.bind(this,"description")}onSelectEditor(e){this.props.onSelect(e)}onSetEditorRef(e,t){this.props.setEditorRef(e,t)}render(){const{socialMediumName:e,onSelectImageClick:t,onRemoveImageClick:i,title:n,titleInputPlaceholder:o,description:r,descriptionInputPlaceholder:a,onTitleChange:c,onDescriptionChange:s,onReplacementVariableSearchChange:l,hoveredField:d,activeField:u,isPremium:p,replacementVariables:g,recommendedReplacementVariables:m,imageWarnings:h,imageUrl:f,imageAltText:b,idSuffix:E}=this.props,S=!!f,v=Object(C.sprintf)(Object(C.__)("%s image","wordpress-seo"),e),I=Object(C.sprintf)(Object(C.__)("%s title","wordpress-seo"),e),w=Object(C.sprintf)(Object(C.__)("%s description","wordpress-seo"),e),T=e.toLowerCase();return Object(A.createElement)(P.Fragment,null,Object(A.createElement)(U,{label:v,onClick:t,onRemoveImageClick:i,warnings:h,imageSelected:S,onMouseEnter:this.onImageEnter,onMouseLeave:this.onLeave,isActive:"image"===u,isHovered:"image"===d,imageUrl:f,imageAltText:b,hasPreview:!p,imageUrlInputId:Object(j.join)([T,"url-input",E]),selectImageButtonId:Object(j.join)([T,"select-button",E]),replaceImageButtonId:Object(j.join)([T,"replace-button",E]),removeImageButtonId:Object(j.join)([T,"remove-button",E])}),Object(A.createElement)(M.ReplacementVariableEditor,{onChange:c,content:n,placeholder:o,replacementVariables:g,recommendedReplacementVariables:m,type:"title",fieldId:Object(j.join)([T,"title-input",E]),label:I,onMouseEnter:this.onTitleEnter,onMouseLeave:this.onLeave,onSearchChange:l,isActive:"title"===u,isHovered:"title"===d,withCaret:!0,onFocus:this.onSelectTitleEditor,onBlur:this.onDeselectEditor,editorRef:this.onTitleEditorRef}),Object(A.createElement)(M.ReplacementVariableEditor,{onChange:s,content:r,placeholder:a,replacementVariables:g,recommendedReplacementVariables:m,type:"description",fieldId:Object(j.join)([T,"description-input",E]),label:w,onMouseEnter:this.onDescriptionEnter,onMouseLeave:this.onLeave,onSearchChange:l,isActive:"description"===u,isHovered:"description"===d,withCaret:!0,onFocus:this.onSelectDescriptionEditor,onBlur:this.onDeselectEditor,editorRef:this.onDescriptionEditorRef}))}}B.propTypes={socialMediumName:L.a.oneOf(["Twitter","Facebook"]).isRequired,onSelectImageClick:L.a.func.isRequired,onRemoveImageClick:L.a.func.isRequired,title:L.a.string.isRequired,description:L.a.string.isRequired,onTitleChange:L.a.func.isRequired,onDescriptionChange:L.a.func.isRequired,onReplacementVariableSearchChange:L.a.func,isPremium:L.a.bool,hoveredField:L.a.string,activeField:L.a.string,onSelect:L.a.func,replacementVariables:M.replacementVariablesShape,recommendedReplacementVariables:L.a.arrayOf(L.a.string),imageWarnings:L.a.array,imageUrl:L.a.string,imageAltText:L.a.string,titleInputPlaceholder:L.a.string,descriptionInputPlaceholder:L.a.string,setEditorRef:L.a.func,onMouseHover:L.a.func,idSuffix:L.a.string},B.defaultProps={replacementVariables:[],recommendedReplacementVariables:[],imageWarnings:[],hoveredField:"",activeField:"",onSelect:()=>{},onReplacementVariableSearchChange:null,imageUrl:"",imageAltText:"",titleInputPlaceholder:"",descriptionInputPlaceholder:"",isPremium:!1,setEditorRef:()=>{},onMouseHover:()=>{},idSuffix:""};var $=B},4:function(e,t){e.exports=window.wp.i18n},5:function(e,t){e.exports=window.yoast.styledComponents},6:function(e,t){e.exports=window.yoast.styleGuide},8:function(e,t){e.exports=window.yoast.componentsNew},9:function(e,t){e.exports=window.yoast.helpers}});