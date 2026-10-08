/**
 * FoundNXT Custom Blocks — Editor
 * Uses useBlockProps for proper delete/select/move support
 */
(function() {
'use strict';

var _blocks    = wp.blocks;
var _el        = wp.element.createElement;
var _useEffect = wp.element.useEffect;
var _useState  = wp.element.useState;
var _Fragment  = wp.element.Fragment;
var _RichText  = wp.blockEditor.RichText;
var _useBlockProps = wp.blockEditor.useBlockProps;
var _InspectorControls = wp.blockEditor.InspectorControls;
var _PanelBody = wp.components.PanelBody;
var _TextControl = wp.components.TextControl;
var _TextareaControl = wp.components.TextareaControl;
var _SelectControl = wp.components.SelectControl;
var _Button    = wp.components.Button;
var _Notice    = wp.components.Notice;

/* ── shared helpers ── */
function updateArr(arr, i, field, val) {
    return arr.map(function(item, j) {
        if (j !== i) return item;
        var copy = Object.assign({}, item);
        copy[field] = val;
        return copy;
    });
}
function addArrItem(arr, tpl) { return arr.concat([Object.assign({}, tpl)]); }
function removeArrItem(arr, i) { return arr.filter(function(_, j) { return j !== i; }); }

/* ── colors ── */
var GREEN      = '#4f46e5';
var GREEN_LITE = '#eef2ff';
var RED_LITE   = '#fef2f2';
var RED        = '#dc2626';
var BLUE_LITE  = '#eff6ff';
var BLUE       = '#2563eb';
var AMB_LITE   = '#fffbeb';
var AMB        = '#d97706';
var BORDER     = '#e5e7eb';
var SURFACE    = '#f7f8fa';
var INK        = '#1a1a2e';
var MUTED      = '#6b7280';

/* ═══════════════════════════════════════════════════
   1. NOTE / CALLOUT
═══════════════════════════════════════════════════ */
_blocks.registerBlockType('foundnxt/note', {
    edit: function(props) {
        var a = props.attributes;
        var type = a.type || 'info';
        var map = {
            info:    { icon:'📌', label:'Note',      bg:BLUE_LITE, border:BLUE,  text:'#1e3a5f' },
            warning: { icon:'⚠️', label:'Warning',   bg:AMB_LITE,  border:AMB,   text:'#3d2a00' },
            success: { icon:'✅', label:'Tip',        bg:GREEN_LITE,border:GREEN, text:'#14532d' },
            danger:  { icon:'🚫', label:'Important', bg:RED_LITE,  border:RED,   text:'#450a0a' },
        };
        var t = map[type];
        var bp = _useBlockProps({ style: { borderLeft:'4px solid '+t.border, background:t.bg, borderRadius:'10px', padding:'16px 20px', margin:'8px 0' } });
        return _el(_Fragment, null,
            _el(_InspectorControls, null,
                _el(_PanelBody, { title:'Note Type', initialOpen:true },
                    _el(_SelectControl, { label:'Type', value:type, options:[
                        {label:'📌 Info (Blue)',    value:'info'},
                        {label:'⚠️ Warning (Yellow)',value:'warning'},
                        {label:'✅ Tip (Green)',     value:'success'},
                        {label:'🚫 Important (Red)', value:'danger'},
                    ], onChange:function(v){ props.setAttributes({type:v}); } })
                )
            ),
            _el('div', bp,
                _el('p', { style:{margin:0, color:t.text, fontSize:'15px', lineHeight:'1.7'} },
                    _el('strong', null, t.icon+' '+t.label+': '),
                    _el(_RichText, { tagName:'span', value:a.content||'', onChange:function(v){props.setAttributes({content:v});}, placeholder:'Add your note here...' })
                )
            )
        );
    },
    save: function() { return null; }
});

/* ═══════════════════════════════════════════════════
   2. KEY TAKEAWAY
═══════════════════════════════════════════════════ */
_blocks.registerBlockType('foundnxt/takeaway', {
    edit: function(props) {
        var bp = _useBlockProps({ style:{background:GREEN_LITE, border:'1px solid #86efac', borderRadius:'12px', padding:'22px 26px', margin:'8px 0', position:'relative'} });
        return _el('div', bp,
            _el('div', { style:{fontSize:'12px', fontWeight:700, textTransform:'uppercase', letterSpacing:'1.5px', color:GREEN, marginBottom:'10px'} }, '💡 KEY TAKEAWAY'),
            _el(_RichText, { tagName:'p', style:{fontSize:'15px', color:'#14532d', fontWeight:500, margin:0, lineHeight:'1.7'}, value:props.attributes.content||'', onChange:function(v){props.setAttributes({content:v});}, placeholder:'Summarise the key insight...' })
        );
    },
    save: function() { return null; }
});

/* ═══════════════════════════════════════════════════
   3. PROS & CONS
═══════════════════════════════════════════════════ */
_blocks.registerBlockType('foundnxt/pros-cons', {
    edit: function(props) {
        var pros = props.attributes.prosItems || [];
        var cons = props.attributes.consItems || [];
        var bp = _useBlockProps({ style:{margin:'8px 0'} });
        function listEditor(items, setProp, color, bgColor, addLabel) {
            return _el('div', { style:{background:bgColor, border:'1px solid '+(color==='#16a34a'?'#86efac':'#fca5a5'), borderRadius:'10px', padding:'18px 20px', flex:1} },
                _el('div', { style:{fontSize:'14px', fontWeight:700, color:color, marginBottom:'12px'} }, (color===GREEN?'👍 Pros':'👎 Cons')),
                items.map(function(item, i) {
                    return _el('div', { key:i, style:{display:'flex', gap:'6px', alignItems:'center', marginBottom:'6px'} },
                        _el('span', { style:{color:color, fontWeight:700, fontSize:'13px', flexShrink:0} }, '•'),
                        _el('input', { type:'text', value:item, onChange:function(e){ var n=[].concat(items); n[i]=e.target.value; props.setAttributes(setProp(n)); }, style:{flex:1, border:'1px solid '+BORDER, borderRadius:'4px', padding:'4px 8px', fontSize:'13px', background:'white'} }),
                        _el('button', { onClick:function(){ props.setAttributes(setProp(items.filter(function(_,j){return j!==i;}))); }, style:{background:'none', border:'none', cursor:'pointer', color:RED, fontSize:'16px', lineHeight:1, padding:'2px 4px'} }, '×')
                    );
                }),
                _el('button', { onClick:function(){ props.setAttributes(setProp(items.concat(['New item']))); }, style:{marginTop:'8px', background:'white', border:'1px dashed '+color, color:color, borderRadius:'6px', padding:'5px 12px', cursor:'pointer', fontSize:'12px', fontWeight:600, width:'100%'} }, addLabel)
            );
        }
        return _el('div', bp,
            _el('div', { style:{display:'grid', gridTemplateColumns:'1fr 1fr', gap:'14px'} },
                listEditor(pros, function(n){return {prosItems:n};}, GREEN, GREEN_LITE, '+ Add Pro'),
                listEditor(cons, function(n){return {consItems:n};}, RED,   RED_LITE,   '+ Add Con')
            )
        );
    },
    save: function() { return null; }
});

/* ═══════════════════════════════════════════════════
   4. STATS ROW
═══════════════════════════════════════════════════ */
_blocks.registerBlockType('foundnxt/stats', {
    edit: function(props) {
        var a = props.attributes;
        var bp = _useBlockProps({ style:{background:SURFACE, border:'1px solid '+BORDER, borderRadius:'12px', padding:'24px', margin:'8px 0'} });
        return _el(_Fragment, null,
            _el(_InspectorControls, null,
                _el(_PanelBody, { title:'Edit Stats', initialOpen:true },
                    ['1','2','3'].map(function(n) {
                        return _el('div', { key:n, style:{marginBottom:'16px', padding:'12px', background:SURFACE, borderRadius:'8px', border:'1px solid '+BORDER} },
                            _el('div', { style:{fontSize:'11px', fontWeight:700, textTransform:'uppercase', color:MUTED, marginBottom:'8px'} }, 'Stat '+n),
                            _el(_TextControl, { label:'Number/Value', value:a['stat'+n]||'', onChange:function(v){var u={};u['stat'+n]=v;props.setAttributes(u);} }),
                            _el(_TextControl, { label:'Label', value:a['label'+n]||'', onChange:function(v){var u={};u['label'+n]=v;props.setAttributes(u);} })
                        );
                    })
                )
            ),
            _el('div', bp,
                _el('div', { style:{display:'grid', gridTemplateColumns:'repeat(3,1fr)', gap:'0'} },
                    ['1','2','3'].map(function(n, idx) {
                        return _el('div', { key:n, style:{textAlign:'center', padding:'16px 20px', borderRight: idx<2 ? '1px solid '+BORDER : 'none'} },
                            _el('div', { style:{fontSize:'clamp(28px,4vw,40px)', fontWeight:900, color:GREEN, lineHeight:1, marginBottom:'6px', fontFamily:"'Source Serif 4',serif"} }, a['stat'+n]||'—'),
                            _el('div', { style:{fontSize:'13px', color:MUTED, lineHeight:1.4} }, a['label'+n]||'Label')
                        );
                    })
                )
            )
        );
    },
    save: function() { return null; }
});

/* ═══════════════════════════════════════════════════
   5. STEPS
═══════════════════════════════════════════════════ */
_blocks.registerBlockType('foundnxt/steps', {
    edit: function(props) {
        var steps = props.attributes.steps || [];
        var heading = props.attributes.heading || 'Step-by-Step Guide';
        var bp = _useBlockProps({ style:{margin:'8px 0'} });
        return _el('div', bp,
            _el('input', { type:'text', value:heading, onChange:function(e){props.setAttributes({heading:e.target.value});}, style:{width:'100%', fontSize:'20px', fontWeight:700, border:'none', borderBottom:'2px solid '+GREEN, padding:'4px 0', marginBottom:'20px', background:'transparent', color:INK, outline:'none'} }),
            steps.map(function(step, i) {
                return _el('div', { key:i, style:{display:'flex', gap:'16px', padding:'16px 0', borderBottom:'1px solid '+BORDER, alignItems:'flex-start'} },
                    _el('div', { style:{fontSize:'22px', fontWeight:900, color:GREEN, fontFamily:"'Source Serif 4',serif", flexShrink:0, width:'36px', lineHeight:1.2} }, String(i+1).padStart(2,'0')),
                    _el('div', { style:{flex:1, display:'flex', flexDirection:'column', gap:'6px'} },
                        _el('input', { type:'text', value:step.title||'', placeholder:'Step title', onChange:function(e){props.setAttributes({steps:updateArr(steps,i,'title',e.target.value)});}, style:{fontSize:'15px', fontWeight:700, border:'none', borderBottom:'1px solid '+BORDER, padding:'4px 0', background:'transparent', color:INK, outline:'none', width:'100%'} }),
                        _el('input', { type:'text', value:step.desc||'', placeholder:'Step description', onChange:function(e){props.setAttributes({steps:updateArr(steps,i,'desc',e.target.value)});}, style:{fontSize:'13px', border:'none', borderBottom:'1px dashed '+BORDER, padding:'4px 0', background:'transparent', color:MUTED, outline:'none', width:'100%'} })
                    ),
                    _el('button', { onClick:function(){props.setAttributes({steps:removeArrItem(steps,i)});}, style:{background:'none',border:'none',cursor:'pointer',color:RED,fontSize:'18px',lineHeight:1,padding:'2px 4px',marginTop:'2px'} }, '×')
                );
            }),
            _el('button', { onClick:function(){props.setAttributes({steps:addArrItem(steps,{num:'0'+steps.length,title:'New step',desc:'Describe this step.'})});}, style:{marginTop:'12px', background:GREEN_LITE, border:'1px dashed '+GREEN, color:GREEN, borderRadius:'8px', padding:'8px 16px', cursor:'pointer', fontSize:'13px', fontWeight:600, width:'100%'} }, '+ Add Step')
        );
    },
    save: function() { return null; }
});

/* ═══════════════════════════════════════════════════
   6. FAQ
═══════════════════════════════════════════════════ */
_blocks.registerBlockType('foundnxt/faq', {
    edit: function(props) {
        var items = props.attributes.items || [];
        var heading = props.attributes.heading || 'Frequently Asked Questions';
        var bp = _useBlockProps({ style:{margin:'8px 0'} });
        return _el('div', bp,
            _el('input', { type:'text', value:heading, onChange:function(e){props.setAttributes({heading:e.target.value});}, style:{width:'100%', fontSize:'20px', fontWeight:700, border:'none', borderBottom:'2px solid '+GREEN, padding:'4px 0', marginBottom:'16px', background:'transparent', color:INK, outline:'none'} }),
            items.map(function(item, i) {
                return _el('div', { key:i, style:{borderBottom:'1px solid '+BORDER, padding:'14px 0'} },
                    _el('div', { style:{display:'flex', gap:'8px', alignItems:'flex-start', marginBottom:'6px'} },
                        _el('div', { style:{background:GREEN, color:'white', fontSize:'10px', fontWeight:800, borderRadius:'4px', padding:'2px 6px', flexShrink:0, marginTop:'3px'} }, 'Q'),
                        _el('input', { type:'text', value:item.q||'', placeholder:'Question', onChange:function(e){props.setAttributes({items:updateArr(items,i,'q',e.target.value)});}, style:{flex:1, fontSize:'15px', fontWeight:600, border:'none', background:'transparent', color:INK, outline:'none', padding:'0'} }),
                        _el('button', { onClick:function(){props.setAttributes({items:removeArrItem(items,i)});}, style:{background:'none',border:'none',cursor:'pointer',color:RED,fontSize:'16px',padding:'0 4px'} }, '×')
                    ),
                    _el('input', { type:'text', value:item.a||'', placeholder:'Answer', onChange:function(e){props.setAttributes({items:updateArr(items,i,'a',e.target.value)});}, style:{width:'100%', fontSize:'14px', border:'none', borderBottom:'1px dashed '+BORDER, background:'transparent', color:MUTED, outline:'none', padding:'4px 0 4px 24px'} })
                );
            }),
            _el('button', { onClick:function(){props.setAttributes({items:addArrItem(items,{q:'New question?',a:'Answer here.'})});}, style:{marginTop:'10px', background:GREEN_LITE, border:'1px dashed '+GREEN, color:GREEN, borderRadius:'8px', padding:'8px 16px', cursor:'pointer', fontSize:'13px', fontWeight:600, width:'100%'} }, '+ Add Question')
        );
    },
    save: function() { return null; }
});

/* ═══════════════════════════════════════════════════
   7. PULL QUOTE
═══════════════════════════════════════════════════ */
_blocks.registerBlockType('foundnxt/pullquote', {
    edit: function(props) {
        var a = props.attributes;
        var bp = _useBlockProps({ style:{background:SURFACE, borderRadius:'12px', padding:'28px 32px', margin:'8px 0', position:'relative', borderLeft:'none'} });
        return _el('blockquote', bp,
            _el('div', { style:{fontSize:'56px', lineHeight:1, color:GREEN, opacity:.2, fontFamily:"'Source Serif 4',serif", marginBottom:'4px', userSelect:'none'} }, '"'),
            _el(_RichText, { tagName:'p', style:{fontSize:'clamp(17px,2vw,20px)', fontStyle:'italic', color:INK, lineHeight:'1.6', margin:'0 0 14px', fontFamily:"'Source Serif 4',serif"}, value:a.quote||'', onChange:function(v){props.setAttributes({quote:v});}, placeholder:'Add your quote here...' }),
            _el('input', { type:'text', value:a.cite||'', placeholder:'— Attribution', onChange:function(e){props.setAttributes({cite:e.target.value});}, style:{fontSize:'13px', fontWeight:600, color:MUTED, border:'none', borderTop:'1px solid '+BORDER, paddingTop:'10px', background:'transparent', outline:'none', width:'100%'} })
        );
    },
    save: function() { return null; }
});

/* ═══════════════════════════════════════════════════
   8. CHECKLIST
═══════════════════════════════════════════════════ */
_blocks.registerBlockType('foundnxt/checklist', {
    edit: function(props) {
        var items = props.attributes.items || [];
        var title = props.attributes.title || 'Checklist';
        var bp = _useBlockProps({ style:{background:'white', border:'1px solid '+BORDER, borderRadius:'12px', padding:'22px 26px', margin:'8px 0'} });
        return _el('div', bp,
            _el('input', { type:'text', value:title, onChange:function(e){props.setAttributes({title:e.target.value});}, style:{fontSize:'14px', fontWeight:700, border:'none', borderBottom:'2px solid '+GREEN, padding:'2px 0 6px', marginBottom:'14px', background:'transparent', color:INK, outline:'none', display:'block'} }),
            items.map(function(item, i) {
                return _el('div', { key:i, style:{display:'flex', gap:'10px', alignItems:'center', marginBottom:'8px'} },
                    _el('div', { style:{width:'20px', height:'20px', borderRadius:'50%', background:GREEN, color:'white', fontSize:'11px', fontWeight:700, display:'flex', alignItems:'center', justifyContent:'center', flexShrink:0} }, '✓'),
                    _el('input', { type:'text', value:item, onChange:function(e){ var n=[].concat(items); n[i]=e.target.value; props.setAttributes({items:n}); }, style:{flex:1, fontSize:'14px', border:'none', borderBottom:'1px dashed '+BORDER, background:'transparent', color:'#374151', outline:'none', padding:'2px 0'} }),
                    _el('button', { onClick:function(){props.setAttributes({items:removeArrItem(items,i)});}, style:{background:'none',border:'none',cursor:'pointer',color:RED,fontSize:'16px',padding:'0 2px'} }, '×')
                );
            }),
            _el('button', { onClick:function(){props.setAttributes({items:items.concat(['New item'])});}, style:{marginTop:'8px', background:GREEN_LITE, border:'1px dashed '+GREEN, color:GREEN, borderRadius:'8px', padding:'6px 14px', cursor:'pointer', fontSize:'12px', fontWeight:600, width:'100%'} }, '+ Add Item')
        );
    },
    save: function() { return null; }
});

/* ═══════════════════════════════════════════════════
   9. VERDICT BOX
═══════════════════════════════════════════════════ */
_blocks.registerBlockType('foundnxt/verdict', {
    edit: function(props) {
        var a = props.attributes;
        var badgeColors = { green:GREEN, blue:BLUE, amber:AMB, red:RED };
        var bc = badgeColors[a.badgeColor||'green'];
        var bp = _useBlockProps({ style:{background:SURFACE, border:'2px solid '+GREEN, borderRadius:'12px', padding:'24px 28px', margin:'8px 0'} });
        return _el(_Fragment, null,
            _el(_InspectorControls, null,
                _el(_PanelBody, { title:'Verdict Settings', initialOpen:true },
                    _el(_TextControl, { label:'Score (e.g. 8.5 / 10)', value:a.score||'', onChange:function(v){props.setAttributes({score:v});} }),
                    _el(_TextControl, { label:'Badge Text', value:a.badge||'', onChange:function(v){props.setAttributes({badge:v});} }),
                    _el(_SelectControl, { label:'Badge Color', value:a.badgeColor||'green', options:[{label:'Green',value:'green'},{label:'Blue',value:'blue'},{label:'Amber',value:'amber'},{label:'Red',value:'red'}], onChange:function(v){props.setAttributes({badgeColor:v});} })
                )
            ),
            _el('div', bp,
                _el('div', { style:{fontSize:'12px', fontWeight:700, textTransform:'uppercase', letterSpacing:'1.5px', color:GREEN, marginBottom:'12px'} }, '⚖️ '+( a.label||'Our Verdict')),
                _el(_RichText, { tagName:'p', style:{fontSize:'15px', color:'#374151', lineHeight:'1.7', margin:'0 0 16px'}, value:a.text||'', onChange:function(v){props.setAttributes({text:v});}, placeholder:'Write your verdict...' }),
                _el('div', { style:{display:'flex', alignItems:'center', gap:'12px', paddingTop:'14px', borderTop:'1px solid '+BORDER} },
                    _el('span', { style:{fontSize:'22px', fontWeight:900, color:GREEN, fontFamily:"'Source Serif 4',serif"} }, a.score||'8.5 / 10'),
                    _el('span', { style:{fontSize:'12px', fontWeight:700, background:bc, color:'white', padding:'3px 12px', borderRadius:'100px', textTransform:'uppercase', letterSpacing:'1px'} }, a.badge||'Recommended')
                )
            )
        );
    },
    save: function() { return null; }
});

/* ═══════════════════════════════════════════════════
   10. TIMELINE
═══════════════════════════════════════════════════ */
_blocks.registerBlockType('foundnxt/timeline', {
    edit: function(props) {
        var items = props.attributes.items || [];
        var heading = props.attributes.heading || 'Timeline';
        var bp = _useBlockProps({ style:{margin:'8px 0'} });
        return _el('div', bp,
            _el('input', { type:'text', value:heading, onChange:function(e){props.setAttributes({heading:e.target.value});}, style:{width:'100%', fontSize:'20px', fontWeight:700, border:'none', borderBottom:'2px solid '+GREEN, padding:'4px 0', marginBottom:'20px', background:'transparent', color:INK, outline:'none'} }),
            items.map(function(item, i) {
                return _el('div', { key:i, style:{display:'flex', gap:'16px', paddingBottom:'18px', alignItems:'flex-start'} },
                    _el('input', { type:'text', value:item.year||'', placeholder:'Year', onChange:function(e){props.setAttributes({items:updateArr(items,i,'year',e.target.value)});}, style:{fontSize:'12px', fontWeight:800, color:GREEN, background:GREEN_LITE, border:'1px solid #86efac', borderRadius:'6px', padding:'4px 6px', width:'64px', textAlign:'center', flexShrink:0, outline:'none'} }),
                    _el('div', { style:{flex:1} },
                        _el('input', { type:'text', value:item.title||'', placeholder:'Event title', onChange:function(e){props.setAttributes({items:updateArr(items,i,'title',e.target.value)});}, style:{width:'100%', fontSize:'15px', fontWeight:700, border:'none', borderBottom:'1px solid '+BORDER, background:'transparent', color:INK, outline:'none', padding:'2px 0', marginBottom:'4px'} }),
                        _el('input', { type:'text', value:item.desc||'', placeholder:'Description', onChange:function(e){props.setAttributes({items:updateArr(items,i,'desc',e.target.value)});}, style:{width:'100%', fontSize:'13px', border:'none', borderBottom:'1px dashed '+BORDER, background:'transparent', color:MUTED, outline:'none', padding:'2px 0'} })
                    ),
                    _el('button', { onClick:function(){props.setAttributes({items:removeArrItem(items,i)});}, style:{background:'none',border:'none',cursor:'pointer',color:RED,fontSize:'18px',padding:'0 2px'} }, '×')
                );
            }),
            _el('button', { onClick:function(){props.setAttributes({items:addArrItem(items,{year:'2025',title:'New event',desc:'Describe it.'})});}, style:{marginTop:'8px', background:GREEN_LITE, border:'1px dashed '+GREEN, color:GREEN, borderRadius:'8px', padding:'8px 16px', cursor:'pointer', fontSize:'13px', fontWeight:600, width:'100%'} }, '+ Add Event')
        );
    },
    save: function() { return null; }
});

/* ═══════════════════════════════════════════════════
   11. FEATURE CARDS
═══════════════════════════════════════════════════ */
_blocks.registerBlockType('foundnxt/feature-cards', {
    edit: function(props) {
        var cards = props.attributes.cards || [];
        var bp = _useBlockProps({ style:{margin:'8px 0'} });
        return _el('div', bp,
            _el('div', { style:{display:'grid', gridTemplateColumns:'repeat(auto-fit,minmax(160px,1fr))', gap:'14px'} },
                cards.map(function(card, i) {
                    return _el('div', { key:i, style:{background:'white', border:'1px solid '+BORDER, borderRadius:'12px', padding:'18px 16px'} },
                        _el('input', { type:'text', value:card.icon||'', placeholder:'Icon', onChange:function(e){props.setAttributes({cards:updateArr(cards,i,'icon',e.target.value)});}, style:{fontSize:'24px', width:'44px', border:'none', background:'transparent', outline:'none', marginBottom:'10px', display:'block'} }),
                        _el('input', { type:'text', value:card.title||'', placeholder:'Title', onChange:function(e){props.setAttributes({cards:updateArr(cards,i,'title',e.target.value)});}, style:{width:'100%', fontSize:'14px', fontWeight:700, border:'none', borderBottom:'1px solid '+BORDER, background:'transparent', color:INK, outline:'none', padding:'2px 0', marginBottom:'6px'} }),
                        _el('input', { type:'text', value:card.desc||'', placeholder:'Description', onChange:function(e){props.setAttributes({cards:updateArr(cards,i,'desc',e.target.value)});}, style:{width:'100%', fontSize:'12px', border:'none', background:'transparent', color:MUTED, outline:'none', padding:'2px 0'} }),
                        _el('button', { onClick:function(){props.setAttributes({cards:removeArrItem(cards,i)});}, style:{marginTop:'8px', background:'none', border:'none', cursor:'pointer', color:RED, fontSize:'12px', padding:0} }, '× Remove')
                    );
                }),
                cards.length < 4 && _el('button', { key:'add', onClick:function(){props.setAttributes({cards:addArrItem(cards,{icon:'⭐',title:'New Feature',desc:'Describe it.'})});}, style:{background:GREEN_LITE, border:'1px dashed '+GREEN, color:GREEN, borderRadius:'12px', padding:'18px', cursor:'pointer', fontSize:'13px', fontWeight:600, minHeight:'100px'} }, '+ Add Card')
            )
        );
    },
    save: function() { return null; }
});

/* ═══════════════════════════════════════════════════
   12. DEFINITION
═══════════════════════════════════════════════════ */
_blocks.registerBlockType('foundnxt/definition', {
    edit: function(props) {
        var a = props.attributes;
        var bp = _useBlockProps({ style:{background:SURFACE, borderLeft:'4px solid '+GREEN, borderRadius:'0 10px 10px 0', padding:'18px 22px', margin:'8px 0'} });
        return _el('div', bp,
            _el('div', { style:{display:'flex', gap:'8px', alignItems:'center', marginBottom:'8px'} },
                _el('input', { type:'text', value:a.term||'', placeholder:'Term', onChange:function(e){props.setAttributes({term:e.target.value});}, style:{fontSize:'18px', fontWeight:800, border:'none', background:'transparent', color:INK, outline:'none', fontFamily:"'Source Serif 4',serif"} }),
                _el('input', { type:'text', value:a.partOfSpeech||'', placeholder:'noun', onChange:function(e){props.setAttributes({partOfSpeech:e.target.value});}, style:{fontSize:'10px', fontWeight:600, color:GREEN, background:GREEN_LITE, border:'1px solid #86efac', borderRadius:'4px', padding:'2px 7px', textTransform:'uppercase', outline:'none', width:'60px'} })
            ),
            _el('input', { type:'text', value:a.definition||'', placeholder:'Write the definition...', onChange:function(e){props.setAttributes({definition:e.target.value});}, style:{width:'100%', fontSize:'14px', color:MUTED, border:'none', borderBottom:'1px dashed '+BORDER, background:'transparent', outline:'none', padding:'4px 0', lineHeight:'1.7'} })
        );
    },
    save: function() { return null; }
});

/* ═══════════════════════════════════════════════════
   13. LEAD PARAGRAPH
═══════════════════════════════════════════════════ */
_blocks.registerBlockType('foundnxt/lead-paragraph', {
    edit: function(props) {
        var bp = _useBlockProps({ style:{fontSize:'clamp(18px,2.2vw,22px)', lineHeight:'1.7', color:INK, borderLeft:'4px solid '+GREEN, paddingLeft:'20px', margin:'8px 0'} });
        return _el(_RichText, Object.assign({}, bp, { tagName:'p', value:props.attributes.content||'', onChange:function(v){props.setAttributes({content:v});}, placeholder:'Write your strong opening paragraph here...' }));
    },
    save: function() { return null; }
});

/* ═══════════════════════════════════════════════════
   14. HIGHLIGHT STRIP
═══════════════════════════════════════════════════ */
_blocks.registerBlockType('foundnxt/highlight', {
    edit: function(props) {
        var bp = _useBlockProps({ style:{background:GREEN, borderRadius:'8px', padding:'14px 20px', margin:'8px 0'} });
        return _el('div', bp,
            _el(_RichText, { tagName:'p', style:{color:'white', fontSize:'15px', fontWeight:500, margin:0, lineHeight:'1.5'}, value:props.attributes.content||'', onChange:function(v){props.setAttributes({content:v});}, placeholder:'🔑 Add a standout fact or stat here...' })
        );
    },
    save: function() { return null; }
});

/* ═══════════════════════════════════════════════════
   15. RISK BANNER
═══════════════════════════════════════════════════ */
_blocks.registerBlockType('foundnxt/risk-banner', {
    edit: function(props) {
        var bp = _useBlockProps({ style:{background:AMB_LITE, border:'1px solid #fde68a', borderRadius:'10px', padding:'16px 20px', display:'flex', gap:'14px', alignItems:'flex-start', margin:'8px 0'} });
        return _el('div', bp,
            _el('div', { style:{fontSize:'20px', flexShrink:0} }, '⚠️'),
            _el(_RichText, { tagName:'p', style:{fontSize:'13px', color:'#6b4c00', margin:0, lineHeight:'1.65'}, value:props.attributes.content||'', onChange:function(v){props.setAttributes({content:v});}, placeholder:'Add your disclaimer text...' })
        );
    },
    save: function() { return null; }
});

/* ═══════════════════════════════════════════════════
   16. SECTION HEADER
═══════════════════════════════════════════════════ */
_blocks.registerBlockType('foundnxt/section-header', {
    edit: function(props) {
        var a = props.attributes;
        var bp = _useBlockProps({ style:{margin:'8px 0 20px', paddingBottom:'20px', borderBottom:'1px solid '+BORDER} });
        return _el('div', bp,
            _el('input', { type:'text', value:a.eyebrow||'', placeholder:'Chapter 01', onChange:function(e){props.setAttributes({eyebrow:e.target.value});}, style:{fontSize:'10px', fontWeight:700, letterSpacing:'3px', textTransform:'uppercase', color:GREEN, background:GREEN_LITE, border:'1px solid #86efac', borderRadius:'100px', padding:'3px 12px', outline:'none', display:'inline-block', marginBottom:'12px'} }),
            _el(_RichText, { tagName:'h2', style:{fontSize:'28px', fontWeight:800, color:INK, margin:'0 0 8px', lineHeight:'1.2'}, value:a.heading||'', onChange:function(v){props.setAttributes({heading:v});}, placeholder:'Section heading...' }),
            _el(_RichText, { tagName:'p', style:{fontSize:'15px', color:MUTED, margin:0}, value:a.subtitle||'', onChange:function(v){props.setAttributes({subtitle:v});}, placeholder:'Optional subtitle...' })
        );
    },
    save: function() { return null; }
});

/* ═══════════════════════════════════════════════════
   17. AUTHOR BIO
═══════════════════════════════════════════════════ */
_blocks.registerBlockType('foundnxt/author-bio', {
    edit: function(props) {
        var a = props.attributes;
        var bp = _useBlockProps({ style:{display:'flex', gap:'18px', alignItems:'flex-start', background:SURFACE, border:'1px solid '+BORDER, borderRadius:'12px', padding:'20px 24px', margin:'8px 0'} });
        return _el(_Fragment, null,
            _el(_InspectorControls, null,
                _el(_PanelBody, { title:'Author Details', initialOpen:true },
                    _el(_TextControl, { label:'Name', value:a.name||'', onChange:function(v){props.setAttributes({name:v});} }),
                    _el(_TextControl, { label:'Role', value:a.role||'', onChange:function(v){props.setAttributes({role:v});} }),
                    _el(_TextControl, { label:'Initials (avatar)', value:a.initials||'', onChange:function(v){props.setAttributes({initials:v});} })
                )
            ),
            _el('div', bp,
                _el('div', { style:{width:'52px', height:'52px', borderRadius:'50%', background:GREEN, color:'white', fontSize:'16px', fontWeight:800, display:'flex', alignItems:'center', justifyContent:'center', flexShrink:0} }, a.initials||'TS'),
                _el('div', { style:{flex:1} },
                    _el('div', { style:{fontSize:'15px', fontWeight:700, color:INK, marginBottom:'2px'} }, a.name||'FoundNXT Editorial Team'),
                    _el('div', { style:{fontSize:'11px', fontWeight:600, color:GREEN, textTransform:'uppercase', letterSpacing:'1px', marginBottom:'8px'} }, a.role||'Author, FoundNXT'),
                    _el(_RichText, { tagName:'p', style:{fontSize:'13.5px', color:MUTED, lineHeight:'1.6', margin:0}, value:a.bio||'', onChange:function(v){props.setAttributes({bio:v});}, placeholder:'Short bio...' })
                )
            )
        );
    },
    save: function() { return null; }
});

/* ═══════════════════════════════════════════════════
   18. BIG NUMBER
═══════════════════════════════════════════════════ */
_blocks.registerBlockType('foundnxt/big-number', {
    edit: function(props) {
        var a = props.attributes;
        var bp = _useBlockProps({ style:{textAlign:'center', padding:'36px 20px', borderTop:'2px solid '+BORDER, borderBottom:'2px solid '+BORDER, margin:'8px 0'} });
        return _el('div', bp,
            _el('input', { type:'text', value:a.figure||'', placeholder:'₹8.4L Cr', onChange:function(e){props.setAttributes({figure:e.target.value});}, style:{fontSize:'clamp(40px,7vw,72px)', fontWeight:900, color:GREEN, fontFamily:"'Source Serif 4',serif", border:'none', background:'transparent', textAlign:'center', outline:'none', display:'block', width:'100%', lineHeight:'1', marginBottom:'12px'} }),
            _el(_RichText, { tagName:'p', style:{fontSize:'14px', color:MUTED, maxWidth:'480px', margin:'0 auto', lineHeight:'1.6'}, value:a.context||'', onChange:function(v){props.setAttributes({context:v});}, placeholder:'Add context for this number...' })
        );
    },
    save: function() { return null; }
});

/* ═══════════════════════════════════════════════════
   19. RELATED READING
═══════════════════════════════════════════════════ */
_blocks.registerBlockType('foundnxt/related-reading', {
    edit: function(props) {
        var links = props.attributes.links || [];
        var label = props.attributes.label || '📚 Related Reading';
        var bp = _useBlockProps({ style:{background:SURFACE, border:'1px solid '+BORDER, borderRadius:'10px', padding:'18px 22px', margin:'8px 0'} });
        return _el('div', bp,
            _el('input', { type:'text', value:label, onChange:function(e){props.setAttributes({label:e.target.value});}, style:{fontSize:'11px', fontWeight:700, textTransform:'uppercase', letterSpacing:'1.5px', color:GREEN, border:'none', background:'transparent', outline:'none', marginBottom:'12px', display:'block'} }),
            links.map(function(link, i) {
                return _el('div', { key:i, style:{display:'flex', gap:'8px', alignItems:'center', marginBottom:'6px'} },
                    _el('span', { style:{color:GREEN, fontSize:'12px'} }, '→'),
                    _el('input', { type:'text', value:link.text||'', placeholder:'Article title', onChange:function(e){props.setAttributes({links:updateArr(links,i,'text',e.target.value)});}, style:{flex:2, fontSize:'14px', color:INK, border:'none', borderBottom:'1px dashed '+BORDER, background:'transparent', outline:'none', padding:'2px 4px'} }),
                    _el('input', { type:'text', value:link.url||'', placeholder:'URL', onChange:function(e){props.setAttributes({links:updateArr(links,i,'url',e.target.value)});}, style:{flex:1, fontSize:'12px', color:MUTED, border:'none', borderBottom:'1px dashed '+BORDER, background:'transparent', outline:'none', padding:'2px 4px'} }),
                    _el('button', { onClick:function(){props.setAttributes({links:removeArrItem(links,i)});}, style:{background:'none',border:'none',cursor:'pointer',color:RED,fontSize:'14px'} }, '×')
                );
            }),
            _el('button', { onClick:function(){props.setAttributes({links:addArrItem(links,{text:'Article title',url:'#'})});}, style:{marginTop:'8px', background:GREEN_LITE, border:'1px dashed '+GREEN, color:GREEN, borderRadius:'6px', padding:'5px 12px', cursor:'pointer', fontSize:'12px', fontWeight:600} }, '+ Add Link')
        );
    },
    save: function() { return null; }
});

/* ═══════════════════════════════════════════════════
   20. CTA BOX
═══════════════════════════════════════════════════ */
_blocks.registerBlockType('foundnxt/cta-box', {
    edit: function(props) {
        var a = props.attributes;
        var bp = _useBlockProps({ style:{background:'linear-gradient(135deg,#4f46e5,#3730a3)', borderRadius:'12px', padding:'32px 36px', textAlign:'center', margin:'8px 0'} });
        return _el(_Fragment, null,
            _el(_InspectorControls, null,
                _el(_PanelBody, { title:'Button Settings', initialOpen:true },
                    _el(_TextControl, { label:'Button Text', value:a.btnText||'', onChange:function(v){props.setAttributes({btnText:v});} }),
                    _el(_TextControl, { label:'Button URL', value:a.btnUrl||'', onChange:function(v){props.setAttributes({btnUrl:v});} })
                )
            ),
            _el('div', bp,
                _el(_RichText, { tagName:'h4', style:{color:'white', fontSize:'20px', fontWeight:800, margin:'0 0 8px'}, value:a.heading||'', onChange:function(v){props.setAttributes({heading:v});}, placeholder:'CTA heading...' }),
                _el(_RichText, { tagName:'p', style:{color:'rgba(255,255,255,0.85)', fontSize:'14px', margin:'0 0 20px', lineHeight:'1.6'}, value:a.text||'', onChange:function(v){props.setAttributes({text:v});}, placeholder:'Supporting text...' }),
                _el('div', { style:{display:'inline-block', background:'white', color:GREEN, fontWeight:700, padding:'.6em 1.8em', borderRadius:'8px', fontSize:'14px'} }, a.btnText||'Subscribe →')
            )
        );
    },
    save: function() { return null; }
});

/* ═══════════════════════════════════════════════════
   21. RESOURCE CARD
═══════════════════════════════════════════════════ */
_blocks.registerBlockType('foundnxt/resource-card', {
    edit: function(props) {
        var a = props.attributes;
        var bp = _useBlockProps({ style:{display:'flex', gap:'16px', alignItems:'center', background:'white', border:'1px solid '+BORDER, borderRadius:'10px', padding:'18px 22px', flexWrap:'wrap', margin:'8px 0'} });
        return _el(_Fragment, null,
            _el(_InspectorControls, null,
                _el(_PanelBody, { title:'Resource Details', initialOpen:true },
                    _el(_TextControl, { label:'Icon (emoji)', value:a.icon||'', onChange:function(v){props.setAttributes({icon:v});} }),
                    _el(_TextControl, { label:'Button Text', value:a.btnText||'', onChange:function(v){props.setAttributes({btnText:v});} }),
                    _el(_TextControl, { label:'Download URL', value:a.btnUrl||'', onChange:function(v){props.setAttributes({btnUrl:v});} })
                )
            ),
            _el('div', bp,
                _el('div', { style:{fontSize:'32px', flexShrink:0, lineHeight:1} }, a.icon||'📄'),
                _el('div', { style:{flex:1, minWidth:0} },
                    _el('input', { type:'text', value:a.title||'', placeholder:'Resource title', onChange:function(e){props.setAttributes({title:e.target.value});}, style:{width:'100%', fontSize:'15px', fontWeight:700, border:'none', borderBottom:'1px solid '+BORDER, background:'transparent', color:INK, outline:'none', padding:'2px 0', marginBottom:'4px'} }),
                    _el('input', { type:'text', value:a.meta||'', placeholder:'PDF · 2.4 MB · Free', onChange:function(e){props.setAttributes({meta:e.target.value});}, style:{width:'100%', fontSize:'12px', color:MUTED, border:'none', background:'transparent', outline:'none', padding:'2px 0'} })
                ),
                _el('div', { style:{background:GREEN, color:'white', fontWeight:600, fontSize:'13px', padding:'.5em 1.4em', borderRadius:'8px', flexShrink:0} }, a.btnText||'Download →')
            )
        );
    },
    save: function() { return null; }
});

}());
