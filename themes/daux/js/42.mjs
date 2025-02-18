export const id=42;export const ids=[42];export const modules={4075:(e,t,n)=>{n.d(t,{M:()=>o});var i=n(9592),r=n(53),a=n(4722);function o(e){var t={options:{directed:e.isDirected(),multigraph:e.isMultigraph(),compound:e.isCompound()},nodes:s(e),edges:d(e)};return i.A(e.graph())||(t.value=r.A(e.graph())),t}function s(e){return a.A(e.nodes(),(function(t){var n=e.node(t),r=e.parent(t),a={v:t};return i.A(n)||(a.value=n),i.A(r)||(a.parent=r),a}))}function d(e){return a.A(e.edges(),(function(t){var n=e.edge(t),r={v:t.v,w:t.w};return i.A(t.name)||(r.name=t.name),i.A(n)||(r.value=n),r}))}n(1471)},5042:(e,t,n)=>{n.d(t,{diagram:()=>z});var i=n(1953),r=n(2207),a=n(6578),o=n(8496),s=n(8252),d=n(7323),l=n(798),c=n(9163),g=n(697),h=n(567),f=n(4075),u={},p={},m={},w=(0,l.K2)((()=>{p={},m={},u={}}),"clear"),y=(0,l.K2)(((e,t)=>(l.Rm.trace("In isDescendant",t," ",e," = ",p[t].includes(e)),!!p[t].includes(e))),"isDescendant"),R=(0,l.K2)(((e,t)=>(l.Rm.info("Descendants of ",t," is ",p[t]),l.Rm.info("Edge is ",e),e.v!==t&&e.w!==t&&(p[t]?p[t].includes(e.v)||y(e.v,t)||y(e.w,t)||p[t].includes(e.w):(l.Rm.debug("Tilt, ",t,",not in descendants"),!1)))),"edgeInCluster"),b=(0,l.K2)(((e,t,n,i)=>{l.Rm.warn("Copying children of ",e,"root",i,"data",t.node(e),i);const r=t.children(e)||[];e!==i&&r.push(e),l.Rm.warn("Copying (nodes) clusterId",e,"nodes",r),r.forEach((r=>{if(t.children(r).length>0)b(r,t,n,i);else{const a=t.node(r);l.Rm.info("cp ",r," to ",i," with parent ",e),n.setNode(r,a),i!==t.parent(r)&&(l.Rm.warn("Setting parent",r,t.parent(r)),n.setParent(r,t.parent(r))),e!==i&&r!==e?(l.Rm.debug("Setting parent",r,e),n.setParent(r,e)):(l.Rm.info("In copy ",e,"root",i,"data",t.node(e),i),l.Rm.debug("Not Setting parent for node=",r,"cluster!==rootId",e!==i,"node!==clusterId",r!==e));const o=t.edges(r);l.Rm.debug("Copying Edges",o),o.forEach((r=>{l.Rm.info("Edge",r);const a=t.edge(r.v,r.w,r.name);l.Rm.info("Edge data",a,i);try{R(r,i)?(l.Rm.info("Copying as ",r.v,r.w,a,r.name),n.setEdge(r.v,r.w,a,r.name),l.Rm.info("newGraph edges ",n.edges(),n.edge(n.edges()[0]))):l.Rm.info("Skipping copy of edge ",r.v,"--\x3e",r.w," rootId: ",i," clusterId:",e)}catch(e){l.Rm.error(e)}}))}l.Rm.debug("Removing node",r),t.removeNode(r)}))}),"copy"),v=(0,l.K2)(((e,t)=>{const n=t.children(e);let i=[...n];for(const r of n)m[r]=e,i=[...i,...v(r,t)];return i}),"extractDescendants"),x=(0,l.K2)(((e,t)=>{l.Rm.trace("Searching",e);const n=t.children(e);if(l.Rm.trace("Searching children of id ",e,n),n.length<1)return l.Rm.trace("This is a valid node",e),e;for(const i of n){const n=x(i,t);if(n)return l.Rm.trace("Found replacement for",e," => ",n),n}}),"findNonClusterChild"),N=(0,l.K2)((e=>u[e]&&u[e].externalConnections&&u[e]?u[e].id:e),"getAnchorId"),D=(0,l.K2)(((e,t)=>{if(!e||t>10)l.Rm.debug("Opting out, no graph ");else{l.Rm.debug("Opting in, graph "),e.nodes().forEach((function(t){e.children(t).length>0&&(l.Rm.warn("Cluster identified",t," Replacement id in edges: ",x(t,e)),p[t]=v(t,e),u[t]={id:x(t,e),clusterData:e.node(t)})})),e.nodes().forEach((function(t){const n=e.children(t),i=e.edges();n.length>0?(l.Rm.debug("Cluster identified",t,p),i.forEach((e=>{e.v!==t&&e.w!==t&&y(e.v,t)^y(e.w,t)&&(l.Rm.warn("Edge: ",e," leaves cluster ",t),l.Rm.warn("Descendants of XXX ",t,": ",p[t]),u[t].externalConnections=!0)}))):l.Rm.debug("Not a cluster ",t,p)}));for(let t of Object.keys(u)){const n=u[t].id,i=e.parent(n);i!==t&&u[i]&&!u[i].externalConnections&&(u[t].id=i)}e.edges().forEach((function(t){const n=e.edge(t);l.Rm.warn("Edge "+t.v+" -> "+t.w+": "+JSON.stringify(t)),l.Rm.warn("Edge "+t.v+" -> "+t.w+": "+JSON.stringify(e.edge(t)));let i=t.v,r=t.w;if(l.Rm.warn("Fix XXX",u,"ids:",t.v,t.w,"Translating: ",u[t.v]," --- ",u[t.w]),u[t.v]&&u[t.w]&&u[t.v]===u[t.w]){l.Rm.warn("Fixing and trixing link to self - removing XXX",t.v,t.w,t.name),l.Rm.warn("Fixing and trixing - removing XXX",t.v,t.w,t.name),i=N(t.v),r=N(t.w),e.removeEdge(t.v,t.w,t.name);const a=t.w+"---"+t.v;e.setNode(a,{domId:a,id:a,labelStyle:"",labelText:n.label,padding:0,shape:"labelRect",style:""});const o=structuredClone(n),s=structuredClone(n);o.label="",o.arrowTypeEnd="none",s.label="",o.fromCluster=t.v,s.toCluster=t.v,e.setEdge(i,a,o,t.name+"-cyclic-special"),e.setEdge(a,r,s,t.name+"-cyclic-special")}else if(u[t.v]||u[t.w]){if(l.Rm.warn("Fixing and trixing - removing XXX",t.v,t.w,t.name),i=N(t.v),r=N(t.w),e.removeEdge(t.v,t.w,t.name),i!==t.v){const r=e.parent(i);u[r].externalConnections=!0,n.fromCluster=t.v}if(r!==t.w){const i=e.parent(r);u[i].externalConnections=!0,n.toCluster=t.w}l.Rm.warn("Fix Replacing with XXX",i,r,t.name),e.setEdge(i,r,n,t.name)}})),l.Rm.warn("Adjusted Graph",f.M(e)),T(e,0),l.Rm.trace(u)}}),"adjustClustersAndEdges"),T=(0,l.K2)(((e,t)=>{if(l.Rm.warn("extractor - ",t,f.M(e),e.children("D")),t>10)return void l.Rm.error("Bailing out");let n=e.nodes(),i=!1;for(const t of n){const n=e.children(t);i=i||n.length>0}if(i){l.Rm.debug("Nodes = ",n,t);for(const i of n)if(l.Rm.debug("Extracting node",i,u,u[i]&&!u[i].externalConnections,!e.parent(i),e.node(i),e.children("D")," Depth ",t),u[i])if(!u[i].externalConnections&&e.children(i)&&e.children(i).length>0){l.Rm.warn("Cluster without external connections, without a parent and with children",i,t);let n="TB"===e.graph().rankdir?"LR":"TB";u[i]?.clusterData?.dir&&(n=u[i].clusterData.dir,l.Rm.warn("Fixing dir",u[i].clusterData.dir,n));const r=new g.T({multigraph:!0,compound:!0}).setGraph({rankdir:n,nodesep:50,ranksep:50,marginx:8,marginy:8}).setDefaultEdgeLabel((function(){return{}}));l.Rm.warn("Old graph before copy",f.M(e)),b(i,e,r,i),e.setNode(i,{clusterNode:!0,id:i,clusterData:u[i].clusterData,labelText:u[i].labelText,graph:r}),l.Rm.warn("New graph after copy node: (",i,")",f.M(r)),l.Rm.debug("Old graph after copy",f.M(e))}else l.Rm.warn("Cluster ** ",i," **not meeting the criteria !externalConnections:",!u[i].externalConnections," no parent: ",!e.parent(i)," children ",e.children(i)&&e.children(i).length>0,e.children("D"),t),l.Rm.debug(u);else l.Rm.debug("Not a cluster",i,t);n=e.nodes(),l.Rm.warn("New list of nodes",n);for(const i of n){const n=e.node(i);l.Rm.warn(" Now next level",i,n),n.clusterNode&&T(n.graph,t+1)}}else l.Rm.debug("Done, no node has children",e.nodes())}),"extractor"),C=(0,l.K2)(((e,t)=>{if(0===t.length)return[];let n=Object.assign(t);return t.forEach((t=>{const i=e.children(t),r=C(e,i);n=[...n,...r]})),n}),"sorter"),S=(0,l.K2)((e=>C(e,e.children())),"sortNodesByHierarchy"),E=(0,l.K2)(((e,t)=>{l.Rm.info("Creating subgraph rect for ",t.id,t);const n=(0,l.D7)(),i=e.insert("g").attr("class","cluster"+(t.class?" "+t.class:"")).attr("id",t.id),r=i.insert("rect",":first-child"),d=(0,l._3)(n.flowchart.htmlLabels),g=i.insert("g").attr("class","cluster-label"),h="markdown"===t.labelType?(0,s.GZ)(g,t.labelText,{style:t.labelStyle,useHtmlLabels:d},n):g.node().appendChild((0,a.DA)(t.labelText,t.labelStyle,void 0,!0));let f=h.getBBox();if((0,l._3)(n.flowchart.htmlLabels)){const e=h.children[0],t=(0,c.Ltv)(h);f=e.getBoundingClientRect(),t.attr("width",f.width),t.attr("height",f.height)}const u=0*t.padding,p=u/2,m=t.width<=f.width+u?f.width+u:t.width;t.width<=f.width+u?t.diff=(f.width-t.width)/2-t.padding/2:t.diff=-t.padding/2,l.Rm.trace("Data ",t,JSON.stringify(t)),r.attr("style",t.style).attr("rx",t.rx).attr("ry",t.ry).attr("x",t.x-m/2).attr("y",t.y-t.height/2-p).attr("width",m).attr("height",t.height+u);const{subGraphTitleTopMargin:w}=(0,o.O)(n);d?g.attr("transform",`translate(${t.x-f.width/2}, ${t.y-t.height/2+w})`):g.attr("transform",`translate(${t.x}, ${t.y-t.height/2+w})`);const y=r.node().getBBox();return t.width=y.width,t.height=y.height,t.intersect=function(e){return(0,a.nM)(t,e)},i}),"rect"),k=(0,l.K2)(((e,t)=>{const n=e.insert("g").attr("class","note-cluster").attr("id",t.id),i=n.insert("rect",":first-child"),r=0*t.padding,o=r/2;i.attr("rx",t.rx).attr("ry",t.ry).attr("x",t.x-t.width/2-o).attr("y",t.y-t.height/2-o).attr("width",t.width+r).attr("height",t.height+r).attr("fill","none");const s=i.node().getBBox();return t.width=s.width,t.height=s.height,t.intersect=function(e){return(0,a.nM)(t,e)},n}),"noteGroup"),X={rect:E,roundedWithTitle:(0,l.K2)(((e,t)=>{const n=(0,l.D7)(),i=e.insert("g").attr("class",t.classes).attr("id",t.id),r=i.insert("rect",":first-child"),s=i.insert("g").attr("class","cluster-label"),d=i.append("rect"),g=s.node().appendChild((0,a.DA)(t.labelText,t.labelStyle,void 0,!0));let h=g.getBBox();if((0,l._3)(n.flowchart.htmlLabels)){const e=g.children[0],t=(0,c.Ltv)(g);h=e.getBoundingClientRect(),t.attr("width",h.width),t.attr("height",h.height)}h=g.getBBox();const f=0*t.padding,u=f/2,p=t.width<=h.width+t.padding?h.width+t.padding:t.width;t.width<=h.width+t.padding?t.diff=(h.width+0*t.padding-t.width)/2:t.diff=-t.padding/2,r.attr("class","outer").attr("x",t.x-p/2-u).attr("y",t.y-t.height/2-u).attr("width",p+f).attr("height",t.height+f),d.attr("class","inner").attr("x",t.x-p/2-u).attr("y",t.y-t.height/2-u+h.height-1).attr("width",p+f).attr("height",t.height+f-h.height-3);const{subGraphTitleTopMargin:m}=(0,o.O)(n);s.attr("transform",`translate(${t.x-h.width/2}, ${t.y-t.height/2-t.padding/3+((0,l._3)(n.flowchart.htmlLabels)?5:3)+m})`);const w=r.node().getBBox();return t.height=w.height,t.intersect=function(e){return(0,a.nM)(t,e)},i}),"roundedWithTitle"),noteGroup:k,divider:(0,l.K2)(((e,t)=>{const n=e.insert("g").attr("class",t.classes).attr("id",t.id),i=n.insert("rect",":first-child"),r=0*t.padding,o=r/2;i.attr("class","divider").attr("x",t.x-t.width/2-o).attr("y",t.y-t.height/2).attr("width",t.width+r).attr("height",t.height+r);const s=i.node().getBBox();return t.width=s.width,t.height=s.height,t.diff=-t.padding/2,t.intersect=function(e){return(0,a.nM)(t,e)},n}),"divider")},K={},M=(0,l.K2)(((e,t)=>{l.Rm.trace("Inserting cluster");const n=t.shape||"rect";K[t.id]=X[n](e,t)}),"insertCluster"),L=(0,l.K2)((()=>{K={}}),"clear"),B=(0,l.K2)((async(e,t,n,r,s,d)=>{l.Rm.info("Graph in recursive render: XXX",f.M(t),s);const c=t.graph().rankdir;l.Rm.trace("Dir in recursive render - dir:",c);const g=e.insert("g").attr("class","root");t.nodes()?l.Rm.info("Recursive render XXX",t.nodes()):l.Rm.info("No nodes found for",t),t.edges().length>0&&l.Rm.trace("Recursive edges",t.edge(t.edges()[0]));const p=g.insert("g").attr("class","clusters"),m=g.insert("g").attr("class","edgePaths"),w=g.insert("g").attr("class","edgeLabels"),y=g.insert("g").attr("class","nodes");await Promise.all(t.nodes().map((async function(e){const i=t.node(e);if(void 0!==s){const n=JSON.parse(JSON.stringify(s.clusterData));l.Rm.info("Setting data for cluster XXX (",e,") ",n,s),t.setNode(s.id,n),t.parent(e)||(l.Rm.trace("Setting parent",e,s.id),t.setParent(e,s.id,n))}if(l.Rm.info("(Insert) Node XXX"+e+": "+JSON.stringify(t.node(e))),i?.clusterNode){l.Rm.info("Cluster identified",e,i.width,t.node(e));const{ranksep:o,nodesep:s}=t.graph();i.graph.setGraph({...i.graph.graph(),ranksep:o,nodesep:s});const c=await B(y,i.graph,n,r,t.node(e),d),g=c.elem;(0,a.lC)(i,g),i.diff=c.diff||0,l.Rm.info("Node bounds (abc123)",e,i,i.width,i.x,i.y),(0,a.U7)(g,i),l.Rm.warn("Recursive render complete ",g,i)}else t.children(e).length>0?(l.Rm.info("Cluster - the non recursive path XXX",e,i.id,i,t),l.Rm.info(x(i.id,t)),u[i.id]={id:x(i.id,t),node:i}):(l.Rm.info("Node - the non recursive path",e,i.id,i),await(0,a.on)(y,t.node(e),c))}))),t.edges().forEach((async function(e){const n=t.edge(e.v,e.w,e.name);l.Rm.info("Edge "+e.v+" -> "+e.w+": "+JSON.stringify(e)),l.Rm.info("Edge "+e.v+" -> "+e.w+": ",e," ",JSON.stringify(t.edge(e))),l.Rm.info("Fix",u,"ids:",e.v,e.w,"Translating: ",u[e.v],u[e.w]),await(0,i.jP)(w,n)})),t.edges().forEach((function(e){l.Rm.info("Edge "+e.v+" -> "+e.w+": "+JSON.stringify(e))})),l.Rm.info("Graph before layout:",JSON.stringify(f.M(t))),l.Rm.info("#############################################"),l.Rm.info("###                Layout                 ###"),l.Rm.info("#############################################"),l.Rm.info(t),(0,h.Zp)(t),l.Rm.info("Graph after layout:",JSON.stringify(f.M(t)));let R=0;const{subGraphTitleTotalMargin:b}=(0,o.O)(d);return S(t).forEach((function(e){const n=t.node(e);l.Rm.info("Position "+e+": "+JSON.stringify(t.node(e))),l.Rm.info("Position "+e+": ("+n.x,","+n.y,") width: ",n.width," height: ",n.height),n?.clusterNode?(n.y+=b,(0,a.U_)(n)):t.children(e).length>0?(n.height+=b,M(p,n),u[n.id].node=n):(n.y+=b/2,(0,a.U_)(n))})),t.edges().forEach((function(e){const a=t.edge(e);l.Rm.info("Edge "+e.v+" -> "+e.w+": "+JSON.stringify(a),a),a.points.forEach((e=>e.y+=b/2));const o=(0,i.Jo)(m,e,a,u,n,t,r);(0,i.T_)(a,o)})),t.nodes().forEach((function(e){const n=t.node(e);l.Rm.info(e,n.type,n.diff),"group"===n.type&&(R=n.diff)})),{elem:g,diff:R}}),"recursiveRender"),O=(0,l.K2)((async(e,t,n,r,o)=>{(0,i.g0)(e,n,r,o),(0,a.IU)(),(0,i.IU)(),L(),w(),l.Rm.warn("Graph at first:",JSON.stringify(f.M(t))),D(t),l.Rm.warn("Graph after:",JSON.stringify(f.M(t)));const s=(0,l.D7)();await B(e,t,r,o,void 0,s)}),"render"),A=(0,l.K2)((e=>l.Y2.sanitizeText(e,(0,l.D7)())),"sanitizeText"),I={dividerMargin:10,padding:5,textHeight:10,curve:void 0},G=(0,l.K2)((function(e,t,n,i){l.Rm.info("keys:",[...e.keys()]),l.Rm.info(e),e.forEach((function(e){const r={shape:"rect",id:e.id,domId:e.domId,labelText:A(e.id),labelStyle:"",style:"fill: none; stroke: black",padding:(0,l.D7)().flowchart?.padding??(0,l.D7)().class?.padding};t.setNode(e.id,r),J(e.classes,t,n,i,e.id),l.Rm.info("setNode",r)}))}),"addNamespaces"),J=(0,l.K2)((function(e,t,n,i,r){l.Rm.info("keys:",[...e.keys()]),l.Rm.info(e),[...e.values()].filter((e=>e.parent===r)).forEach((function(e){const n=e.cssClasses.join(" "),a=(0,d.sM)(e.styles),o=e.label??e.id,s={labelStyle:a.labelStyle,shape:"class_box",labelText:A(o),classData:e,rx:0,ry:0,class:n,style:a.style,id:e.id,domId:e.domId,tooltip:i.db.getTooltip(e.id,r)||"",haveCallback:e.haveCallback,link:e.link,width:"group"===e.type?500:void 0,type:e.type,padding:(0,l.D7)().flowchart?.padding??(0,l.D7)().class?.padding};t.setNode(e.id,s),r&&t.setParent(e.id,r),l.Rm.info("setNode",s)}))}),"addClasses"),_=(0,l.K2)((function(e,t,n,i){l.Rm.info(e),e.forEach((function(e,r){const a=e,o=a.text,s={labelStyle:"",shape:"note",labelText:A(o),noteData:a,rx:0,ry:0,class:"",style:"",id:a.id,domId:a.id,tooltip:"",type:"note",padding:(0,l.D7)().flowchart?.padding??(0,l.D7)().class?.padding};if(t.setNode(a.id,s),l.Rm.info("setNode",s),!a.class||!i.has(a.class))return;const g=n+r,h={id:`edgeNote${g}`,classes:"relation",pattern:"dotted",arrowhead:"none",startLabelRight:"",endLabelLeft:"",arrowTypeStart:"none",arrowTypeEnd:"none",style:"fill:none",labelStyle:"",curve:(0,d.Ib)(I.curve,c.lUB)};t.setEdge(a.id,a.class,h,g)}))}),"addNotes"),P=(0,l.K2)((function(e,t){const n=(0,l.D7)().flowchart;let i=0;e.forEach((function(e){i++;const r={classes:"relation",pattern:1==e.relation.lineType?"dashed":"solid",id:(0,d.rY)(e.id1,e.id2,{prefix:"id",counter:i}),arrowhead:"arrow_open"===e.type?"none":"normal",startLabelRight:"none"===e.relationTitle1?"":e.relationTitle1,endLabelLeft:"none"===e.relationTitle2?"":e.relationTitle2,arrowTypeStart:U(e.relation.type1),arrowTypeEnd:U(e.relation.type2),style:"fill:none",labelStyle:"",curve:(0,d.Ib)(n?.curve,c.lUB)};if(l.Rm.info(r,e),void 0!==e.style){const t=(0,d.sM)(e.style);r.style=t.style,r.labelStyle=t.labelStyle}e.text=e.title,void 0===e.text?void 0!==e.style&&(r.arrowheadStyle="fill: #333"):(r.arrowheadStyle="fill: #333",r.labelpos="c",(0,l.D7)().flowchart?.htmlLabels??(0,l.D7)().htmlLabels?(r.labelType="html",r.label='<span class="edgeLabel">'+e.text+"</span>"):(r.labelType="text",r.label=e.text.replace(l.Y2.lineBreakRegex,"\n"),void 0===e.style&&(r.style=r.style||"stroke: #333; stroke-width: 1.5px;fill:none"),r.labelStyle=r.labelStyle.replace("color:","fill:"))),t.setEdge(e.id1,e.id2,r,i)}))}),"addRelations"),$=(0,l.K2)((function(e){I={...I,...e}}),"setConf"),F=(0,l.K2)((async function(e,t,n,i){l.Rm.info("Drawing class - ",t);const r=(0,l.D7)().flowchart??(0,l.D7)().class,a=(0,l.D7)().securityLevel;l.Rm.info("config:",r);const o=r?.nodeSpacing??50,s=r?.rankSpacing??50,h=new g.T({multigraph:!0,compound:!0}).setGraph({rankdir:i.db.getDirection(),nodesep:o,ranksep:s,marginx:8,marginy:8}).setDefaultEdgeLabel((function(){return{}})),f=i.db.getNamespaces(),u=i.db.getClasses(),p=i.db.getRelations(),m=i.db.getNotes();let w;l.Rm.info(p),G(f,h,t,i),J(u,h,t,i),P(p,h),_(m,h,p.length+1,u),"sandbox"===a&&(w=(0,c.Ltv)("#i"+t));const y="sandbox"===a?(0,c.Ltv)(w.nodes()[0].contentDocument.body):(0,c.Ltv)("body"),R=y.select(`[id="${t}"]`),b=y.select("#"+t+" g");if(await O(b,h,["aggregation","extension","composition","dependency","lollipop"],"classDiagram",t),d._K.insertTitle(R,"classTitleText",r?.titleTopMargin??5,i.db.getDiagramTitle()),(0,l.ot)(h,R,r?.diagramPadding,r?.useMaxWidth),!r?.htmlLabels){const e="sandbox"===a?w.nodes()[0].contentDocument:document,n=e.querySelectorAll('[id="'+t+'"] .edgeLabel .label');for(const t of n){const n=t.getBBox(),i=e.createElementNS("http://www.w3.org/2000/svg","rect");i.setAttribute("rx",0),i.setAttribute("ry",0),i.setAttribute("width",n.width),i.setAttribute("height",n.height),t.insertBefore(i,t.firstChild)}}}),"draw");function U(e){let t;switch(e){case 0:t="aggregation";break;case 1:t="extension";break;case 2:t="composition";break;case 3:t="dependency";break;case 4:t="lollipop";break;default:t="none"}return t}(0,l.K2)(U,"getArrowMarker");var j={setConf:$,draw:F},z={parser:r._$,db:r.z2,renderer:j,styles:r.tM,init:(0,l.K2)((e=>{e.class||(e.class={}),e.class.arrowMarkerAbsolute=e.arrowMarkerAbsolute,r.z2.clear()}),"init")}}};