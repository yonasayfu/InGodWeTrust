import{d as f,$ as c,c as n,o as m,w as r,i as a,f as _,g as d,a as o,u as s,Z as g,t as w,b as x,k as l}from"./app-DRlL913_.js";import{_ as k,a as y,b}from"./Label.vue_vue_type_script_setup_true_lang-DMi3k3ua.js";import{_ as v}from"./TextLink.vue_vue_type_script_setup_true_lang-tvM_c-P9.js";import{_ as V}from"./AppLogoIcon.vue_vue_type_script_setup_true_lang-BY4cWZt9.js";import{L as $,_ as C}from"./AuthLayout.vue_vue_type_script_setup_true_lang-DJG9XUE7.js";/* empty css            */import"./index-CKP9qmX2.js";const B={key:0,class:"mb-4 text-center text-sm font-medium text-green-600"},E={class:"space-y-6"},N={class:"grid gap-2"},F={class:"my-6 flex items-center justify-start"},h={class:"space-x-1 text-center text-sm text-muted-foreground"},U=f({__name:"ForgotPassword",props:{status:{}},setup(L){const t=c({email:""}),p=()=>{t.post(route("password.email"))};return(i,e)=>(m(),n(C,{title:"Forgot password",description:"Enter your email to receive a password reset link"},{default:r(()=>[a(s(g),{title:"Forgot password"}),i.status?(m(),_("div",B,w(i.status),1)):d("",!0),o("div",E,[o("form",{onSubmit:x(p,["prevent"])},[o("div",N,[a(s(k),{for:"email"},{default:r(()=>e[1]||(e[1]=[l("Email address")])),_:1}),a(s(y),{id:"email",type:"email",name:"email",autocomplete:"off",modelValue:s(t).email,"onUpdate:modelValue":e[0]||(e[0]=u=>s(t).email=u),autofocus:"",placeholder:"email@example.com"},null,8,["modelValue"]),a(b,{message:s(t).errors.email},null,8,["message"])]),o("div",F,[a(s(V),{class:"w-full",disabled:s(t).processing},{default:r(()=>[s(t).processing?(m(),n(s($),{key:0,class:"h-4 w-4 animate-spin"})):d("",!0),e[2]||(e[2]=l(" Email password reset link "))]),_:1},8,["disabled"])])],32),o("div",h,[e[4]||(e[4]=o("span",null,"Or, return to",-1)),a(v,{href:i.route("login")},{default:r(()=>e[3]||(e[3]=[l("log in")])),_:1},8,["href"])])])]),_:1}))}});export{U as default};
