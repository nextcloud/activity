"use strict";(self.webpackChunkactivity=self.webpackChunkactivity||[]).push([[819],{51819:(e,t,l)=>{l.r(t),l.d(t,{confirm:()=>p,filepicker:()=>c});var o=l(43935),a=l(83468),i=(l(48588),l(20144)),n=l(28169);const s='<svg xmlns="http://www.w3.org/2000/svg" id="mdi-folder-multiple" viewBox="0 0 24 24"><path d="M22,4H14L12,2H6A2,2 0 0,0 4,4V16A2,2 0 0,0 6,18H22A2,2 0 0,0 24,16V6A2,2 0 0,0 22,4M2,6H0V11H0V20A2,2 0 0,0 2,22H20V20H2V6Z" /></svg>',r='<svg xmlns="http://www.w3.org/2000/svg" id="mdi-folder-move" viewBox="0 0 24 24"><path d="M14,18V15H10V11H14V8L19,13M20,6H12L10,4H4C2.89,4 2,4.89 2,6V18A2,2 0 0,0 4,20H20A2,2 0 0,0 22,18V8C22,6.89 21.1,6 20,6Z" /></svg>',u=(e,t,l=(()=>{}))=>{const o=document.createElement("div");(document.querySelector(null==t?void 0:t.container)||document.body).appendChild(o);const a=new i.default({el:o,name:"VueDialogHelper",render:o=>o(e,{props:t,on:{close:()=>{l(),a.$destroy()}}})})};async function c(e,t,l=!1,i,n,c,p,m){const d=(e,t)=>{const o=e=>{const t=(null==e?void 0:e.root)||"";let l=(null==e?void 0:e.path)||"";return l.startsWith(t)&&(l=l.slice(t.length)||"/"),l};return l?l=>e(l.map(o),t):l=>e(o(l[0]),t)};let v;c===a.a.Custom?(v=[],(m.buttons||[]).forEach((e=>{v.push({callback:d(t,e.type),label:e.text,type:e.defaultButton?"primary":"secondary"})}))):v=(e,i)=>{var n,u,p;const m=[],v=(null==(u=null==(n=null==e?void 0:e[0])?void 0:n.attributes)?void 0:u.displayName)||(null==(p=null==e?void 0:e[0])?void 0:p.basename),y=v||(0,o.basename)(i);return c===a.a.Choose&&m.push({callback:d(t,a.a.Choose),label:v&&!l?(0,a.t)("Choose {file}",{file:v}):(0,a.t)("Choose"),type:"primary"}),(c===a.a.CopyMove||c===a.a.Copy)&&m.push({callback:d(t,a.a.Copy),label:y?(0,a.t)("Copy to {target}",{target:y}):(0,a.t)("Copy"),type:"primary",icon:s}),(c===a.a.Move||c===a.a.CopyMove)&&m.push({callback:d(t,a.a.Move),label:y?(0,a.t)("Move to {target}",{target:y}):(0,a.t)("Move"),type:c===a.a.Move?"primary":"secondary",icon:r}),m};const y={};"function"==typeof(null==m?void 0:m.filter)&&(y.filterFn=e=>m.filter((e=>{var t,l,o,a,i,n;return{id:e.fileid||null,path:e.path,mimetype:e.mime||null,mtime:(null==(t=e.mtime)?void 0:t.getTime())||null,permissions:e.permissions,name:(null==(l=e.attributes)?void 0:l.displayname)||e.basename,etag:(null==(o=e.attributes)?void 0:o.etag)||null,hasPreview:(null==(a=e.attributes)?void 0:a.hasPreview)||null,mountType:(null==(i=e.attributes)?void 0:i.mountType)||null,quotaAvailableBytes:(null==(n=e.attributes)?void 0:n.quotaAvailableBytes)||null,icon:null,sharePermissions:null}})(e)));const b="string"==typeof i?[i]:i||[];u(a.c,{...y,name:e,buttons:v,multiselect:l,path:p,mimetypeFilter:b,allowPickDirectory:!0===(null==m?void 0:m.allowDirectoryChooser)||b.includes("httpd/unix-directory")})}async function p(e,t,l,o){u(n.D,{name:t,message:e,buttons:[{label:"No",callback:()=>l(!1)},{label:"Yes",type:"primary",callback:()=>l(!0)}],size:"small"},(()=>l(!1)))}}}]);
//# sourceMappingURL=activity-819.js.map?v=8ef638c64f9d239fe780