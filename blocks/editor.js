/**
 * FoundNXT Custom Blocks — Editor Registration
 * Complete Gutenberg editor implementation with RichText inline editing,
 * live WYSIWYG preview, and Sidebar "Quick Paste" multi-line textareas.
 *
 * @package FoundNXT
 */
(function() {
    'use strict';

    var _blocks = wp.blocks;
    var _el = wp.element.createElement;
    var _Fragment = wp.element.Fragment;
    var _RichText = wp.blockEditor.RichText;
    var _useBlockProps = wp.blockEditor.useBlockProps;
    var _InspectorControls = wp.blockEditor.InspectorControls;
    var _PanelBody = wp.components.PanelBody;
    var _TextControl = wp.components.TextControl;
    var _TextareaControl = wp.components.TextareaControl;
    var _SelectControl = wp.components.SelectControl;
    var _Button = wp.components.Button;
    var _RangeControl = wp.components.RangeControl;

    var BRAND = '#FF5A2C';
    var BRAND_SUBTLE = 'rgba(255, 90, 44, 0.08)';
    var BRAND_BORDER = 'rgba(255, 90, 44, 0.28)';

    function arrayToLines(arr, mapper) {
        if (!Array.isArray(arr)) return typeof arr === 'string' ? arr : '';
        return arr.map(mapper || function(item) {
            return typeof item === 'string' ? item : JSON.stringify(item);
        }).join('\n');
    }

    /* ═══════════════════════════════════════════════════
       1. KEY TAKEAWAYS (foundnxt/takeaway)
       ═══════════════════════════════════════════════ */
    _blocks.registerBlockType('foundnxt/takeaway', {
        edit: function(props) {
            var a = props.attributes;
            var bp = _useBlockProps({
                style: {
                    background: BRAND_SUBTLE,
                    border: '1px solid ' + BRAND_BORDER,
                    borderLeft: '4px solid ' + BRAND,
                    borderRadius: '14px',
                    padding: '24px 28px',
                    margin: '16px 0',
                    fontFamily: "'Plus Jakarta Sans', sans-serif"
                }
            });
            return _el(_Fragment, null,
                _el(_InspectorControls, null,
                    _el(_PanelBody, { title: 'Takeaway Settings', initialOpen: true },
                        _el(_TextControl, {
                            label: 'Header Label',
                            value: a.title || '',
                            onChange: function(v) { props.setAttributes({ title: v }); }
                        }),
                        _el(_TextareaControl, {
                            label: 'Quick Paste Content (Markdown / Multi-line)',
                            help: 'Paste multi-line points or paragraphs directly.',
                            rows: 6,
                            value: a.content || '',
                            onChange: function(v) { props.setAttributes({ content: v }); }
                        })
                    )
                ),
                _el('div', bp,
                    _el('div', { style: { marginBottom: '12px' } },
                        _el('span', {
                            style: {
                                display: 'inline-flex',
                                alignItems: 'center',
                                gap: '6px',
                                fontFamily: "'Sora', sans-serif",
                                fontSize: '11px',
                                fontWeight: 700,
                                textTransform: 'uppercase',
                                letterSpacing: '1.5px',
                                color: BRAND,
                                background: 'rgba(255, 90, 44, 0.12)',
                                border: '1px solid ' + BRAND_BORDER,
                                padding: '4px 12px',
                                borderRadius: '9999px'
                            }
                        }, '💡 ' + (a.title || 'Key Takeaways'))
                    ),
                    _el(_RichText, {
                        tagName: 'div',
                        style: { fontSize: '15px', color: '#334155', lineHeight: 1.75, outline: 'none' },
                        value: a.content || '',
                        onChange: function(v) { props.setAttributes({ content: v }); },
                        placeholder: 'Enter key takeaways or paste multi-line bullet points...'
                    })
                )
            );
        },
        save: function() { return null; }
    });

    /* ═══════════════════════════════════════════════════
       2. CALLOUT NOTE (foundnxt/note)
       ═══════════════════════════════════════════════ */
    _blocks.registerBlockType('foundnxt/note', {
        edit: function(props) {
            var a = props.attributes;
            var type = a.type || 'tip';
            var map = {
                tip:     { icon: '💡', title: 'Founder Pro Tip', color: BRAND, bg: BRAND_SUBTLE, bdr: BRAND_BORDER },
                info:    { icon: 'ℹ️', title: 'Important Note',   color: '#2563eb', bg: '#eff6ff', bdr: '#bfdbfe' },
                warning: { icon: '⚠️', title: 'Caution / Warning',color: '#d97706', bg: '#fffbeb', bdr: '#fde68a' },
                danger:  { icon: '🚫', title: 'Critical Risk',   color: '#dc2626', bg: '#fef2f2', bdr: '#fca5a5' }
            };
            var config = map[type] || map.tip;
            var text = a.text || a.content || '';

            var bp = _useBlockProps({
                style: {
                    background: config.bg,
                    border: '1px solid ' + config.bdr,
                    borderLeft: '4px solid ' + config.color,
                    borderRadius: '12px',
                    padding: '18px 22px',
                    margin: '16px 0',
                    fontFamily: "'Plus Jakarta Sans', sans-serif"
                }
            });

            return _el(_Fragment, null,
                _el(_InspectorControls, null,
                    _el(_PanelBody, { title: 'Callout Settings', initialOpen: true },
                        _el(_SelectControl, {
                            label: 'Callout Style',
                            value: type,
                            options: [
                                { label: '💡 Pro Tip (Brand Orange)', value: 'tip' },
                                { label: 'ℹ️ Information (Blue)',      value: 'info' },
                                { label: '⚠️ Warning (Amber)',        value: 'warning' },
                                { label: '🚫 Critical Risk (Red)',     value: 'danger' }
                            ],
                            onChange: function(v) { props.setAttributes({ type: v }); }
                        }),
                        _el(_TextControl, {
                            label: 'Callout Title',
                            value: a.title !== undefined ? a.title : config.title,
                            onChange: function(v) { props.setAttributes({ title: v }); }
                        })
                    )
                ),
                _el('div', bp,
                    _el('div', { style: { display: 'flex', alignItems: 'center', gap: '8px', marginBottom: '8px' } },
                        _el('span', { style: { fontSize: '18px' } }, config.icon),
                        _el('strong', {
                            style: { fontFamily: "'Sora', sans-serif", fontSize: '14.5px', color: config.color, fontWeight: 700 }
                        }, a.title || config.title)
                    ),
                    _el(_RichText, {
                        tagName: 'div',
                        style: { fontSize: '14.5px', color: '#334155', lineHeight: 1.7 },
                        value: text,
                        onChange: function(v) { props.setAttributes({ text: v, content: v }); },
                        placeholder: 'Write callout content here...'
                    })
                )
            );
        },
        save: function() { return null; }
    });

    /* ═══════════════════════════════════════════════════
       3. PROS & CONS (foundnxt/pros-cons)
       ═══════════════════════════════════════════════ */
    _blocks.registerBlockType('foundnxt/pros-cons', {
        edit: function(props) {
            var a = props.attributes;
            var pros = Array.isArray(a.pros) ? a.pros : (typeof a.pros === 'string' ? a.pros.split('\n') : (a.prosItems || []));
            var cons = Array.isArray(a.cons) ? a.cons : (typeof a.cons === 'string' ? a.cons.split('\n') : (a.consItems || []));

            var bp = _useBlockProps({
                style: { margin: '16px 0', fontFamily: "'Plus Jakarta Sans', sans-serif" }
            });

            return _el(_Fragment, null,
                _el(_InspectorControls, null,
                    _el(_PanelBody, { title: 'Quick Paste Pros & Cons', initialOpen: true },
                        _el(_TextControl, {
                            label: 'Pros Column Header',
                            value: a.prosTitle || 'Advantages',
                            onChange: function(v) { props.setAttributes({ prosTitle: v }); }
                        }),
                        _el(_TextareaControl, {
                            label: 'Quick Paste Pros (One per line)',
                            rows: 5,
                            value: pros.join('\n'),
                            onChange: function(v) {
                                var list = v.split('\n').filter(function(s) { return s.trim().length > 0; });
                                props.setAttributes({ pros: list, prosItems: list });
                            }
                        }),
                        _el(_TextControl, {
                            label: 'Cons Column Header',
                            value: a.consTitle || 'Trade-offs & Risks',
                            onChange: function(v) { props.setAttributes({ consTitle: v }); }
                        }),
                        _el(_TextareaControl, {
                            label: 'Quick Paste Cons (One per line)',
                            rows: 5,
                            value: cons.join('\n'),
                            onChange: function(v) {
                                var list = v.split('\n').filter(function(s) { return s.trim().length > 0; });
                                props.setAttributes({ cons: list, consItems: list });
                            }
                        })
                    )
                ),
                _el('div', bp,
                    _el('div', { style: { display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '16px' } },
                        _el('div', { style: { background: 'rgba(16, 185, 129, 0.08)', border: '1px solid rgba(16, 185, 129, 0.25)', borderRadius: '12px', padding: '20px' } },
                            _el('div', { style: { fontFamily: "'Sora', sans-serif", fontSize: '13px', fontWeight: 700, color: '#047857', marginBottom: '14px', textTransform: 'uppercase', letterSpacing: '1px' } }, '✓ ' + (a.prosTitle || 'Advantages')),
                            _el('ul', { style: { margin: 0, paddingLeft: '18px', display: 'flex', flexDirection: 'column', gap: '8px', fontSize: '14px', color: '#334155' } },
                                pros.map(function(item, i) { return _el('li', { key: i }, item); })
                            )
                        ),
                        _el('div', { style: { background: 'rgba(239, 68, 68, 0.08)', border: '1px solid rgba(239, 68, 68, 0.25)', borderRadius: '12px', padding: '20px' } },
                            _el('div', { style: { fontFamily: "'Sora', sans-serif", fontSize: '13px', fontWeight: 700, color: '#b91c1c', marginBottom: '14px', textTransform: 'uppercase', letterSpacing: '1px' } }, '✕ ' + (a.consTitle || 'Trade-offs & Risks')),
                            _el('ul', { style: { margin: 0, paddingLeft: '18px', display: 'flex', flexDirection: 'column', gap: '8px', fontSize: '14px', color: '#334155' } },
                                cons.map(function(item, i) { return _el('li', { key: i }, item); })
                            )
                        )
                    )
                )
            );
        },
        save: function() { return null; }
    });

    /* ═══════════════════════════════════════════════════
       4. STATS GRID (foundnxt/stats)
       ═══════════════════════════════════════════════ */
    _blocks.registerBlockType('foundnxt/stats', {
        edit: function(props) {
            var a = props.attributes;
            var stats = a.stats || [];
            if (!stats.length && a.stat1) {
                stats = [
                    { val: a.stat1, label: a.label1 || '', trend: '', trendDirection: 'up' },
                    { val: a.stat2 || '', label: a.label2 || '', trend: '', trendDirection: 'up' },
                    { val: a.stat3 || '', label: a.label3 || '', trend: '', trendDirection: 'up' }
                ];
            }
            var bp = _useBlockProps({
                style: {
                    background: '#f8fafc',
                    border: '1px solid #e2e8f0',
                    borderRadius: '16px',
                    padding: '24px',
                    margin: '16px 0',
                    fontFamily: "'Plus Jakarta Sans', sans-serif"
                }
            });

            return _el(_Fragment, null,
                _el(_InspectorControls, null,
                    _el(_PanelBody, { title: 'Stats Settings', initialOpen: true },
                        _el(_RangeControl, {
                            label: 'Columns',
                            value: a.columns || 3,
                            min: 2,
                            max: 4,
                            onChange: function(v) { props.setAttributes({ columns: v }); }
                        }),
                        _el(_TextControl, {
                            label: 'Source Citation',
                            value: a.source || '',
                            onChange: function(v) { props.setAttributes({ source: v }); }
                        }),
                        _el('div', { style: { marginTop: '14px', marginBottom: '8px', fontWeight: 600 } }, 'Stat Items:'),
                        stats.map(function(stat, i) {
                            return _el('div', { key: i, style: { padding: '10px', background: '#fff', border: '1px solid #e2e8f0', borderRadius: '8px', marginBottom: '10px' } },
                                _el(_TextControl, {
                                    label: 'Value (e.g. $4.2B, 98%)',
                                    value: stat.val || '',
                                    onChange: function(v) {
                                        var copy = stats.slice();
                                        copy[i] = Object.assign({}, copy[i], { val: v });
                                        props.setAttributes({ stats: copy });
                                    }
                                }),
                                _el(_TextControl, {
                                    label: 'Label',
                                    value: stat.label || '',
                                    onChange: function(v) {
                                        var copy = stats.slice();
                                        copy[i] = Object.assign({}, copy[i], { label: v });
                                        props.setAttributes({ stats: copy });
                                    }
                                }),
                                _el(_TextControl, {
                                    label: 'Trend Badge (e.g. +28% YoY)',
                                    value: stat.trend || '',
                                    onChange: function(v) {
                                        var copy = stats.slice();
                                        copy[i] = Object.assign({}, copy[i], { trend: v });
                                        props.setAttributes({ stats: copy });
                                    }
                                }),
                                _el(_Button, {
                                    isDestructive: true,
                                    isSmall: true,
                                    onClick: function() {
                                        var copy = stats.filter(function(_, idx) { return idx !== i; });
                                        props.setAttributes({ stats: copy });
                                    }
                                }, 'Remove Stat')
                            );
                        }),
                        _el(_Button, {
                            isPrimary: true,
                            onClick: function() {
                                var copy = stats.concat([{ val: '100+', label: 'New Metric', trend: '+15%', trendDirection: 'up' }]);
                                props.setAttributes({ stats: copy });
                            }
                        }, '+ Add Stat Card')
                    )
                ),
                _el('div', bp,
                    _el('div', { style: { display: 'grid', gridTemplateColumns: 'repeat(' + (a.columns || 3) + ', minmax(0, 1fr))', gap: '16px' } },
                        stats.map(function(stat, i) {
                            return _el('div', { key: i, style: { background: '#fff', border: '1px solid #e2e8f0', borderRadius: '12px', padding: '18px', textAlign: 'center' } },
                                _el('div', { style: { fontFamily: "'Sora', sans-serif", fontSize: '28px', fontWeight: 800, color: BRAND, lineHeight: 1.1, marginBottom: '6px' } }, stat.val || '—'),
                                _el('div', { style: { fontSize: '13px', fontWeight: 600, color: '#0E1220', marginBottom: '6px' } }, stat.label || 'Label'),
                                stat.trend ? _el('span', { style: { display: 'inline-block', fontSize: '11px', fontFamily: "'JetBrains Mono', monospace", color: '#047857', background: 'rgba(16, 185, 129, 0.1)', padding: '2px 8px', borderRadius: '9999px' } }, stat.trend) : null
                            );
                        })
                    ),
                    a.source ? _el('div', { style: { textAlign: 'right', fontSize: '12px', color: '#64748B', marginTop: '12px', fontStyle: 'italic' } }, 'Source: ' + a.source) : null
                )
            );
        },
        save: function() { return null; }
    });

    /* ═══════════════════════════════════════════════════
       5. STEP-BY-STEP (foundnxt/steps)
       ═══════════════════════════════════════════════ */
    _blocks.registerBlockType('foundnxt/steps', {
        edit: function(props) {
            var a = props.attributes;
            var steps = Array.isArray(a.steps) ? a.steps : [];

            var bp = _useBlockProps({
                style: { margin: '16px 0', fontFamily: "'Plus Jakarta Sans', sans-serif" }
            });

            return _el(_Fragment, null,
                _el(_InspectorControls, null,
                    _el(_PanelBody, { title: 'Quick Paste Steps Framework', initialOpen: true },
                        _el(_TextControl, {
                            label: 'Guide Heading',
                            value: a.heading || '',
                            onChange: function(v) { props.setAttributes({ heading: v }); }
                        }),
                        _el(_TextareaControl, {
                            label: 'Quick Paste Steps (Title | Description per line)',
                            help: 'Format: "Validate Problem | Conduct 25 discovery calls." on each line.',
                            rows: 6,
                            value: steps.map(function(s) { return (s.title || '') + ' | ' + (s.desc || ''); }).join('\n'),
                            onChange: function(v) {
                                var lines = v.split('\n').filter(function(l) { return l.trim().length > 0; });
                                var parsed = lines.map(function(line) {
                                    var parts = line.split('|');
                                    return {
                                        title: parts[0] ? parts[0].trim() : '',
                                        desc: parts[1] ? parts[1].trim() : ''
                                    };
                                });
                                props.setAttributes({ steps: parsed });
                            }
                        })
                    )
                ),
                _el('div', bp,
                    a.heading ? _el('h3', { style: { fontFamily: "'Sora', sans-serif", fontSize: '20px', fontWeight: 700, color: '#0E1220', marginBottom: '18px' } }, a.heading) : null,
                    _el('div', { style: { display: 'flex', flexDirection: 'column', gap: '16px' } },
                        steps.map(function(step, i) {
                            return _el('div', { key: i, style: { display: 'flex', gap: '16px', alignItems: 'flex-start' } },
                                _el('div', {
                                    style: {
                                        width: '38px',
                                        height: '38px',
                                        borderRadius: '50%',
                                        background: 'linear-gradient(135deg, #FF5A2C 0%, #FF7A45 100%)',
                                        color: '#fff',
                                        fontFamily: "'Sora', sans-serif",
                                        fontWeight: 800,
                                        fontSize: '14px',
                                        display: 'flex',
                                        alignItems: 'center',
                                        justifyContent: 'center',
                                        flexShrink: 0
                                    }
                                }, String(i + 1).padStart(2, '0')),
                                _el('div', { style: { flex: 1 } },
                                    _el('div', { style: { fontFamily: "'Sora', sans-serif", fontSize: '16px', fontWeight: 700, color: '#0E1220', marginBottom: '4px' } }, step.title || 'Step ' + (i + 1)),
                                    _el('div', { style: { fontSize: '14px', color: '#64748B', lineHeight: 1.6 } }, step.desc || 'Step description...')
                                )
                            );
                        })
                    )
                )
            );
        },
        save: function() { return null; }
    });

    /* ═══════════════════════════════════════════════════
       6. FAQ (foundnxt/faq)
       ═══════════════════════════════════════════════ */
    _blocks.registerBlockType('foundnxt/faq', {
        edit: function(props) {
            var a = props.attributes;
            var items = Array.isArray(a.items) ? a.items : [];

            var bp = _useBlockProps({
                style: { margin: '16px 0', fontFamily: "'Plus Jakarta Sans', sans-serif" }
            });

            return _el(_Fragment, null,
                _el(_InspectorControls, null,
                    _el(_PanelBody, { title: 'FAQ Settings & Quick Paste', initialOpen: true },
                        _el(_TextControl, {
                            label: 'FAQ Heading',
                            value: a.title || a.heading || 'Frequently Asked Questions',
                            onChange: function(v) { props.setAttributes({ title: v, heading: v }); }
                        }),
                        _el(_TextareaControl, {
                            label: 'Quick Paste FAQs (Question | Answer per line)',
                            help: 'One FAQ per line: What is X? | X is...',
                            rows: 7,
                            value: items.map(function(it) { return (it.q || '') + ' | ' + (it.a || ''); }).join('\n'),
                            onChange: function(v) {
                                var lines = v.split('\n').filter(function(l) { return l.trim().length > 0; });
                                var parsed = lines.map(function(line) {
                                    var parts = line.split('|');
                                    return {
                                        q: parts[0] ? parts[0].trim() : '',
                                        a: parts[1] ? parts[1].trim() : ''
                                    };
                                });
                                props.setAttributes({ items: parsed });
                            }
                        })
                    )
                ),
                _el('div', bp,
                    _el('h3', { style: { fontFamily: "'Sora', sans-serif", fontSize: '20px', fontWeight: 700, color: '#0E1220', marginBottom: '16px' } }, a.title || a.heading || 'Frequently Asked Questions'),
                    _el('div', { style: { display: 'flex', flexDirection: 'column', gap: '10px' } },
                        items.map(function(item, i) {
                            return _el('div', { key: i, style: { background: '#f8fafc', border: '1px solid #e2e8f0', borderRadius: '10px', padding: '14px 18px' } },
                                _el('div', { style: { fontFamily: "'Sora', sans-serif", fontSize: '15px', fontWeight: 600, color: '#0E1220', marginBottom: '6px' } }, 'Q: ' + (item.q || 'Question...')),
                                _el('div', { style: { fontSize: '14px', color: '#64748B', lineHeight: 1.6 } }, item.a || 'Answer...')
                            );
                        })
                    )
                )
            );
        },
        save: function() { return null; }
    });

    /* ═══════════════════════════════════════════════════
       7. PULLQUOTE (foundnxt/pullquote)
       ═══════════════════════════════════════════════ */
    _blocks.registerBlockType('foundnxt/pullquote', {
        edit: function(props) {
            var a = props.attributes;
            var bp = _useBlockProps({
                style: {
                    background: '#f8fafc',
                    borderLeft: '4px solid ' + BRAND,
                    borderRadius: '16px',
                    padding: '28px 32px',
                    margin: '18px 0',
                    fontFamily: "'Plus Jakarta Sans', sans-serif"
                }
            });

            return _el(_Fragment, null,
                _el(_InspectorControls, null,
                    _el(_PanelBody, { title: 'Quote Details', initialOpen: true },
                        _el(_TextControl, {
                            label: 'Author Name',
                            value: a.author || a.citation || '',
                            onChange: function(v) { props.setAttributes({ author: v, citation: v }); }
                        }),
                        _el(_TextControl, {
                            label: 'Author Role / Company',
                            value: a.role || '',
                            onChange: function(v) { props.setAttributes({ role: v }); }
                        })
                    )
                ),
                _el('div', bp,
                    _el('div', { style: { fontSize: '32px', color: BRAND, lineHeight: 1, marginBottom: '8px', opacity: 0.3 } }, '“'),
                    _el(_RichText, {
                        tagName: 'p',
                        style: { fontFamily: "'Sora', sans-serif", fontSize: '18px', fontWeight: 600, fontStyle: 'italic', color: '#0E1220', margin: '0 0 14px', lineHeight: 1.55 },
                        value: a.quote || a.content || '',
                        onChange: function(v) { props.setAttributes({ quote: v, content: v }); },
                        placeholder: 'Enter quote text...'
                    }),
                    _el('div', { style: { display: 'flex', gap: '8px', fontSize: '13px', borderTop: '1px solid #e2e8f0', paddingTop: '10px' } },
                        _el('strong', { style: { color: '#0E1220' } }, a.author || a.citation || 'Author Name'),
                        a.role ? _el('span', { style: { color: '#64748B' } }, '· ' + a.role) : null
                    )
                )
            );
        },
        save: function() { return null; }
    });

    /* ═══════════════════════════════════════════════════
       8. CHECKLIST (foundnxt/checklist)
       ═══════════════════════════════════════════════ */
    _blocks.registerBlockType('foundnxt/checklist', {
        edit: function(props) {
            var a = props.attributes;
            var items = Array.isArray(a.items) ? a.items : (typeof a.items === 'string' ? a.items.split('\n') : []);

            var bp = _useBlockProps({
                style: {
                    background: '#f8fafc',
                    border: '1px solid #e2e8f0',
                    borderRadius: '14px',
                    padding: '22px 26px',
                    margin: '16px 0',
                    fontFamily: "'Plus Jakarta Sans', sans-serif"
                }
            });

            return _el(_Fragment, null,
                _el(_InspectorControls, null,
                    _el(_PanelBody, { title: 'Quick Paste Checklist', initialOpen: true },
                        _el(_TextControl, {
                            label: 'Checklist Title',
                            value: a.title || '',
                            onChange: function(v) { props.setAttributes({ title: v }); }
                        }),
                        _el(_TextareaControl, {
                            label: 'Quick Paste Items (One item per line)',
                            rows: 6,
                            value: items.join('\n'),
                            onChange: function(v) {
                                var list = v.split('\n').filter(function(s) { return s.trim().length > 0; });
                                props.setAttributes({ items: list });
                            }
                        })
                    )
                ),
                _el('div', bp,
                    _el('div', { style: { marginBottom: '14px' } },
                        _el('span', {
                            style: {
                                display: 'inline-flex',
                                alignItems: 'center',
                                gap: '6px',
                                fontFamily: "'Sora', sans-serif",
                                fontSize: '11px',
                                fontWeight: 700,
                                textTransform: 'uppercase',
                                letterSpacing: '1.5px',
                                color: BRAND,
                                background: BRAND_SUBTLE,
                                border: '1px solid ' + BRAND_BORDER,
                                padding: '4px 12px',
                                borderRadius: '9999px'
                            }
                        }, '✓ ' + (a.title || 'Checklist'))
                    ),
                    _el('ul', { style: { margin: 0, padding: 0, listStyle: 'none', display: 'flex', flexDirection: 'column', gap: '10px' } },
                        items.map(function(item, i) {
                            return _el('li', { key: i, style: { display: 'flex', alignItems: 'center', gap: '10px', fontSize: '14.5px', color: '#334155' } },
                                _el('span', {
                                    style: {
                                        width: '20px',
                                        height: '20px',
                                        borderRadius: '50%',
                                        background: BRAND,
                                        color: '#fff',
                                        display: 'flex',
                                        alignItems: 'center',
                                        justifyContent: 'center',
                                        fontSize: '11px',
                                        fontWeight: 700,
                                        flexShrink: 0
                                    }
                                }, '✓'),
                                _el('span', null, item)
                            );
                        })
                    )
                )
            );
        },
        save: function() { return null; }
    });

    /* ═══════════════════════════════════════════════════
       9. VERDICT (foundnxt/verdict)
       ═══════════════════════════════════════════════ */
    _blocks.registerBlockType('foundnxt/verdict', {
        edit: function(props) {
            var a = props.attributes;
            var bp = _useBlockProps({
                style: {
                    background: '#f8fafc',
                    border: '2px solid ' + BRAND,
                    borderRadius: '16px',
                    padding: '24px 28px',
                    margin: '18px 0',
                    fontFamily: "'Plus Jakarta Sans', sans-serif"
                }
            });

            return _el(_Fragment, null,
                _el(_InspectorControls, null,
                    _el(_PanelBody, { title: 'Verdict Details', initialOpen: true },
                        _el(_TextControl, {
                            label: 'Product / Subject Title',
                            value: a.product || '',
                            onChange: function(v) { props.setAttributes({ product: v }); }
                        }),
                        _el(_TextControl, {
                            label: 'Score (e.g. 9.4)',
                            value: a.score || a.rating || '9.0',
                            onChange: function(v) { props.setAttributes({ score: v, rating: v }); }
                        }),
                        _el(_TextControl, {
                            label: 'Recommendation Badge',
                            value: a.badge || 'Highly Recommended',
                            onChange: function(v) { props.setAttributes({ badge: v }); }
                        })
                    )
                ),
                _el('div', bp,
                    _el('div', { style: { display: 'flex', alignItems: 'center', justifyContent: 'space-between', marginBottom: '12px' } },
                        _el('div', null,
                            _el('div', { style: { fontSize: '11px', fontFamily: "'Sora', sans-serif", fontWeight: 700, textTransform: 'uppercase', color: BRAND, letterSpacing: '1.5px' } }, 'FoundNXT Verdict'),
                            _el('h4', { style: { fontFamily: "'Sora', sans-serif", fontSize: '20px', fontWeight: 700, color: '#0E1220', margin: '4px 0 0' } }, a.product || 'Enterprise Product Review')
                        ),
                        _el('div', { style: { background: BRAND_SUBTLE, border: '1px solid ' + BRAND_BORDER, padding: '6px 14px', borderRadius: '10px', textAlign: 'center' } },
                            _el('span', { style: { fontFamily: "'Sora', sans-serif", fontSize: '26px', fontWeight: 800, color: BRAND } }, a.score || a.rating || '9.0'),
                            _el('span', { style: { fontSize: '13px', color: '#64748B' } }, '/10')
                        )
                    ),
                    _el('div', { style: { marginBottom: '14px' } },
                        _el('span', { style: { background: BRAND, color: '#fff', fontSize: '11px', fontWeight: 700, textTransform: 'uppercase', letterSpacing: '1px', padding: '3px 10px', borderRadius: '9999px' } }, a.badge || 'Highly Recommended')
                    ),
                    _el(_RichText, {
                        tagName: 'p',
                        style: { fontSize: '15px', color: '#334155', lineHeight: 1.7, margin: 0 },
                        value: a.take || a.text || '',
                        onChange: function(v) { props.setAttributes({ take: v, text: v }); },
                        placeholder: 'Summarize the final verdict...'
                    })
                )
            );
        },
        save: function() { return null; }
    });

    /* ═══════════════════════════════════════════════════
       10. TIMELINE (foundnxt/timeline)
       ═══════════════════════════════════════════════ */
    _blocks.registerBlockType('foundnxt/timeline', {
        edit: function(props) {
            var a = props.attributes;
            var events = Array.isArray(a.events) ? a.events : [];

            var bp = _useBlockProps({
                style: { margin: '16px 0', fontFamily: "'Plus Jakarta Sans', sans-serif" }
            });

            return _el(_Fragment, null,
                _el(_InspectorControls, null,
                    _el(_PanelBody, { title: 'Quick Paste Timeline', initialOpen: true },
                        _el(_TextControl, {
                            label: 'Timeline Heading',
                            value: a.heading || '',
                            onChange: function(v) { props.setAttributes({ heading: v }); }
                        }),
                        _el(_TextareaControl, {
                            label: 'Quick Paste Milestones (Year | Title | Description)',
                            rows: 6,
                            value: events.map(function(e) { return (e.year || '') + ' | ' + (e.title || '') + ' | ' + (e.desc || ''); }).join('\n'),
                            onChange: function(v) {
                                var lines = v.split('\n').filter(function(l) { return l.trim().length > 0; });
                                var parsed = lines.map(function(line) {
                                    var parts = line.split('|');
                                    return {
                                        year: parts[0] ? parts[0].trim() : '',
                                        title: parts[1] ? parts[1].trim() : '',
                                        desc: parts[2] ? parts[2].trim() : ''
                                    };
                                });
                                props.setAttributes({ events: parsed });
                            }
                        })
                    )
                ),
                _el('div', bp,
                    a.heading ? _el('h3', { style: { fontFamily: "'Sora', sans-serif", fontSize: '20px', fontWeight: 700, color: '#0E1220', marginBottom: '18px' } }, a.heading) : null,
                    _el('div', { style: { display: 'flex', flexDirection: 'column', gap: '14px' } },
                        events.map(function(ev, i) {
                            return _el('div', { key: i, style: { background: '#f8fafc', border: '1px solid #e2e8f0', borderRadius: '10px', padding: '14px 18px' } },
                                _el('span', { style: { fontFamily: "'JetBrains Mono', monospace", fontSize: '11px', fontWeight: 700, color: BRAND, background: BRAND_SUBTLE, border: '1px solid ' + BRAND_BORDER, padding: '2px 8px', borderRadius: '4px' } }, ev.year || '2026'),
                                _el('div', { style: { fontFamily: "'Sora', sans-serif", fontSize: '15px', fontWeight: 700, color: '#0E1220', margin: '6px 0 4px' } }, ev.title || 'Milestone Title'),
                                _el('div', { style: { fontSize: '13.5px', color: '#64748B' } }, ev.desc || 'Milestone description...')
                            );
                        })
                    )
                )
            );
        },
        save: function() { return null; }
    });

    /* ═══════════════════════════════════════════════════
       11. DEFINITION (foundnxt/definition)
       ═══════════════════════════════════════════════ */
    _blocks.registerBlockType('foundnxt/definition', {
        edit: function(props) {
            var a = props.attributes;
            var bp = _useBlockProps({
                style: {
                    background: '#f8fafc',
                    borderLeft: '4px solid ' + BRAND,
                    borderRadius: '0 12px 12px 0',
                    padding: '20px 24px',
                    margin: '16px 0',
                    fontFamily: "'Plus Jakarta Sans', sans-serif"
                }
            });

            return _el(_Fragment, null,
                _el(_InspectorControls, null,
                    _el(_PanelBody, { title: 'Definition Details', initialOpen: true },
                        _el(_TextControl, {
                            label: 'Term',
                            value: a.term || '',
                            onChange: function(v) { props.setAttributes({ term: v }); }
                        }),
                        _el(_TextControl, {
                            label: 'Part of Speech / Category',
                            value: a.partOfSpeech || a.tag || 'Noun / Metric',
                            onChange: function(v) { props.setAttributes({ partOfSpeech: v, tag: v }); }
                        })
                    )
                ),
                _el('div', bp,
                    _el('div', { style: { display: 'flex', alignItems: 'center', gap: '10px', marginBottom: '8px' } },
                        _el('strong', { style: { fontFamily: "'Sora', sans-serif", fontSize: '18px', fontWeight: 800, color: '#0E1220' } }, a.term || 'Term Name'),
                        _el('span', { style: { fontFamily: "'JetBrains Mono', monospace", fontSize: '11px', fontWeight: 600, color: BRAND, background: BRAND_SUBTLE, border: '1px solid ' + BRAND_BORDER, padding: '2px 8px', borderRadius: '4px' } }, a.partOfSpeech || a.tag || 'Noun')
                    ),
                    _el(_RichText, {
                        tagName: 'p',
                        style: { fontSize: '14.5px', color: '#334155', lineHeight: 1.7, margin: 0 },
                        value: a.definition || a.text || '',
                        onChange: function(v) { props.setAttributes({ definition: v, text: v }); },
                        placeholder: 'Enter comprehensive definition...'
                    })
                )
            );
        },
        save: function() { return null; }
    });

    /* ═══════════════════════════════════════════════════
       12. CTA BOX (foundnxt/cta-box)
       ═══════════════════════════════════════════════ */
    _blocks.registerBlockType('foundnxt/cta-box', {
        edit: function(props) {
            var a = props.attributes;
            var bp = _useBlockProps({
                style: {
                    background: '#0E1220',
                    color: '#ffffff',
                    borderRadius: '16px',
                    padding: '36px 32px',
                    margin: '20px 0',
                    textAlign: 'center',
                    fontFamily: "'Plus Jakarta Sans', sans-serif"
                }
            });

            return _el(_Fragment, null,
                _el(_InspectorControls, null,
                    _el(_PanelBody, { title: 'CTA Box Settings', initialOpen: true },
                        _el(_TextControl, {
                            label: 'Eyebrow Tag',
                            value: a.tag || '',
                            onChange: function(v) { props.setAttributes({ tag: v }); }
                        }),
                        _el(_TextControl, {
                            label: 'Title',
                            value: a.title || '',
                            onChange: function(v) { props.setAttributes({ title: v }); }
                        }),
                        _el(_TextareaControl, {
                            label: 'Description',
                            rows: 3,
                            value: a.desc || '',
                            onChange: function(v) { props.setAttributes({ desc: v }); }
                        }),
                        _el(_TextControl, {
                            label: 'Button Text',
                            value: a.btnText || '',
                            onChange: function(v) { props.setAttributes({ btnText: v }); }
                        }),
                        _el(_TextControl, {
                            label: 'Button URL',
                            value: a.btnUrl || '',
                            onChange: function(v) { props.setAttributes({ btnUrl: v }); }
                        })
                    )
                ),
                _el('div', bp,
                    a.tag ? _el('span', { style: { display: 'inline-block', fontSize: '11px', fontFamily: "'Sora', sans-serif", fontWeight: 700, textTransform: 'uppercase', letterSpacing: '2px', color: BRAND, background: 'rgba(255, 90, 44, 0.12)', border: '1px solid ' + BRAND_BORDER, padding: '4px 12px', borderRadius: '9999px', marginBottom: '14px' } }, a.tag) : null,
                    _el('h3', { style: { fontFamily: "'Sora', sans-serif", fontSize: '24px', fontWeight: 800, color: '#ffffff', margin: '0 0 10px', lineHeight: 1.3 } }, a.title || 'Call to Action Title'),
                    _el('p', { style: { fontSize: '14.5px', color: '#94A3B8', maxWidth: '520px', margin: '0 auto 20px', lineHeight: 1.6 } }, a.desc || 'Persuasive lead generation description...'),
                    _el('span', { style: { display: 'inline-block', background: BRAND, color: '#ffffff', fontFamily: "'Sora', sans-serif", fontSize: '14px', fontWeight: 700, padding: '10px 24px', borderRadius: '8px' } }, a.btnText || 'Subscribe Free →')
                )
            );
        },
        save: function() { return null; }
    });

    /* ═══════════════════════════════════════════════════
       13. RESOURCE CARD (foundnxt/resource-card)
       ═══════════════════════════════════════════════ */
    _blocks.registerBlockType('foundnxt/resource-card', {
        edit: function(props) {
            var a = props.attributes;
            var bp = _useBlockProps({
                style: {
                    background: '#f8fafc',
                    border: '1px solid #e2e8f0',
                    borderRadius: '12px',
                    padding: '18px 22px',
                    margin: '16px 0',
                    display: 'flex',
                    alignItems: 'center',
                    gap: '16px',
                    fontFamily: "'Plus Jakarta Sans', sans-serif"
                }
            });

            return _el(_Fragment, null,
                _el(_InspectorControls, null,
                    _el(_PanelBody, { title: 'Resource Settings', initialOpen: true },
                        _el(_TextControl, {
                            label: 'Icon Emoji',
                            value: a.icon || '📄',
                            onChange: function(v) { props.setAttributes({ icon: v }); }
                        }),
                        _el(_TextControl, {
                            label: 'Title',
                            value: a.title || '',
                            onChange: function(v) { props.setAttributes({ title: v }); }
                        }),
                        _el(_TextControl, {
                            label: 'Metadata (Format, Size, Pages)',
                            value: a.meta || '',
                            onChange: function(v) { props.setAttributes({ meta: v }); }
                        }),
                        _el(_TextControl, {
                            label: 'Button Text',
                            value: a.btnText || 'Download Free',
                            onChange: function(v) { props.setAttributes({ btnText: v }); }
                        }),
                        _el(_TextControl, {
                            label: 'Button URL',
                            value: a.btnUrl || '#',
                            onChange: function(v) { props.setAttributes({ btnUrl: v }); }
                        })
                    )
                ),
                _el('div', bp,
                    _el('div', { style: { width: '44px', height: '44px', borderRadius: '8px', background: BRAND_SUBTLE, border: '1px solid ' + BRAND_BORDER, display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: '20px', flexShrink: 0 } }, a.icon || '📄'),
                    _el('div', { style: { flex: 1 } },
                        _el('div', { style: { fontFamily: "'Sora', sans-serif", fontSize: '15px', fontWeight: 700, color: '#0E1220' } }, a.title || 'Resource Title'),
                        _el('div', { style: { fontSize: '12.5px', color: '#64748B' } }, a.meta || 'PDF · 4.2 MB')
                    ),
                    _el('span', { style: { background: BRAND, color: '#fff', fontSize: '13px', fontWeight: 600, padding: '7px 16px', borderRadius: '6px', whiteSpace: 'nowrap' } }, a.btnText || 'Download Free')
                )
            );
        },
        save: function() { return null; }
    });

    /* ═══════════════════════════════════════════════════
       14. FEATURE CARDS (foundnxt/feature-cards)
       ═══════════════════════════════════════════════ */
    _blocks.registerBlockType('foundnxt/feature-cards', {
        edit: function(props) {
            var a = props.attributes;
            var cards = a.cards || [];

            var bp = _useBlockProps({
                style: { margin: '16px 0', fontFamily: "'Plus Jakarta Sans', sans-serif" }
            });

            return _el(_Fragment, null,
                _el(_InspectorControls, null,
                    _el(_PanelBody, { title: 'Feature Cards Settings', initialOpen: true },
                        _el(_TextControl, {
                            label: 'Grid Headline',
                            value: a.headline || '',
                            onChange: function(v) { props.setAttributes({ headline: v }); }
                        }),
                        _el(_RangeControl, {
                            label: 'Columns',
                            value: a.columns || 3,
                            min: 2,
                            max: 4,
                            onChange: function(v) { props.setAttributes({ columns: v }); }
                        }),
                        _el(_Button, {
                            isPrimary: true,
                            onClick: function() {
                                var copy = cards.concat([{ icon: '🚀', title: 'New Feature', desc: 'Feature description.', tag: 'Highlight' }]);
                                props.setAttributes({ cards: copy });
                            }
                        }, '+ Add Feature Card')
                    )
                ),
                _el('div', bp,
                    a.headline ? _el('h3', { style: { fontFamily: "'Sora', sans-serif", fontSize: '20px', fontWeight: 700, color: '#0E1220', marginBottom: '16px' } }, a.headline) : null,
                    _el('div', { style: { display: 'grid', gridTemplateColumns: 'repeat(' + (a.columns || 3) + ', minmax(0, 1fr))', gap: '14px' } },
                        cards.map(function(card, i) {
                            return _el('div', { key: i, style: { background: '#f8fafc', border: '1px solid #e2e8f0', borderRadius: '12px', padding: '18px' } },
                                _el('div', { style: { display: 'flex', justifyContent: 'space-between', marginBottom: '8px' } },
                                    _el('span', { style: { fontSize: '22px' } }, card.icon || '⭐'),
                                    card.tag ? _el('span', { style: { fontFamily: "'JetBrains Mono', monospace", fontSize: '10px', color: BRAND, background: BRAND_SUBTLE, padding: '2px 6px', borderRadius: '4px' } }, card.tag) : null
                                ),
                                _el('div', { style: { fontFamily: "'Sora', sans-serif", fontSize: '15px', fontWeight: 700, color: '#0E1220', marginBottom: '4px' } }, card.title || 'Feature Title'),
                                _el('div', { style: { fontSize: '13px', color: '#64748B', lineHeight: 1.6 } }, card.desc || 'Feature description...')
                            );
                        })
                    )
                )
            );
        },
        save: function() { return null; }
    });

    /* ═══════════════════════════════════════════════════
       15. BIG NUMBER (foundnxt/big-number)
       ═══════════════════════════════════════════════ */
    _blocks.registerBlockType('foundnxt/big-number', {
        edit: function(props) {
            var a = props.attributes;
            var bp = _useBlockProps({
                style: {
                    background: '#f8fafc',
                    border: '1px solid #e2e8f0',
                    borderRadius: '16px',
                    padding: '36px 24px',
                    margin: '18px 0',
                    textAlign: 'center',
                    fontFamily: "'Plus Jakarta Sans', sans-serif"
                }
            });

            return _el(_Fragment, null,
                _el(_InspectorControls, null,
                    _el(_PanelBody, { title: 'Big Number Settings', initialOpen: true },
                        _el(_TextControl, {
                            label: 'Eyebrow Label',
                            value: a.label || '',
                            onChange: function(v) { props.setAttributes({ label: v }); }
                        }),
                        _el(_TextControl, {
                            label: 'Badge (e.g. +145% YoY)',
                            value: a.badge || '',
                            onChange: function(v) { props.setAttributes({ badge: v }); }
                        }),
                        _el(_TextControl, {
                            label: 'Big Number Figure (e.g. ₹8.4L Cr)',
                            value: a.figure || '',
                            onChange: function(v) { props.setAttributes({ figure: v }); }
                        }),
                        _el(_TextareaControl, {
                            label: 'Context Narrative',
                            rows: 3,
                            value: a.context || '',
                            onChange: function(v) { props.setAttributes({ context: v }); }
                        }),
                        _el(_TextControl, {
                            label: 'Source Citation',
                            value: a.source || '',
                            onChange: function(v) { props.setAttributes({ source: v }); }
                        })
                    )
                ),
                _el('div', bp,
                    (a.label || a.badge) ? _el('div', { style: { display: 'flex', justifyContent: 'center', gap: '8px', marginBottom: '10px' } },
                        a.label ? _el('span', { style: { fontFamily: "'Sora', sans-serif", fontSize: '11px', fontWeight: 700, textTransform: 'uppercase', letterSpacing: '1.5px', color: '#64748B' } }, a.label) : null,
                        a.badge ? _el('span', { style: { fontFamily: "'JetBrains Mono', monospace", fontSize: '11px', color: BRAND, background: BRAND_SUBTLE, padding: '2px 8px', borderRadius: '9999px' } }, a.badge) : null
                    ) : null,
                    _el('div', { style: { fontFamily: "'Sora', sans-serif", fontSize: '48px', fontWeight: 900, color: BRAND, lineHeight: 1.1, marginBottom: '8px' } }, a.figure || '₹1Cr'),
                    _el('p', { style: { fontSize: '15px', color: '#334155', maxWidth: '480px', margin: '0 auto', lineHeight: 1.6 } }, a.context || 'Context narrative explaining this metric.'),
                    a.source ? _el('div', { style: { fontSize: '12px', color: '#64748B', marginTop: '12px', fontStyle: 'italic' } }, 'Source: ' + a.source) : null
                )
            );
        },
        save: function() { return null; }
    });

    /* ═══════════════════════════════════════════════════
       16. RELATED READING (foundnxt/related-reading)
       ═══════════════════════════════════════════════ */
    _blocks.registerBlockType('foundnxt/related-reading', {
        edit: function(props) {
            var a = props.attributes;
            var links = Array.isArray(a.links) ? a.links : [];

            var bp = _useBlockProps({
                style: {
                    background: '#f8fafc',
                    border: '1px solid #e2e8f0',
                    borderLeft: '4px solid ' + BRAND,
                    borderRadius: '12px',
                    padding: '20px 24px',
                    margin: '16px 0',
                    fontFamily: "'Plus Jakarta Sans', sans-serif"
                }
            });

            return _el(_Fragment, null,
                _el(_InspectorControls, null,
                    _el(_PanelBody, { title: 'Quick Paste Related Reading', initialOpen: true },
                        _el(_TextControl, {
                            label: 'Label',
                            value: a.label || '',
                            onChange: function(v) { props.setAttributes({ label: v }); }
                        }),
                        _el(_TextareaControl, {
                            label: 'Quick Paste Links (Title | URL per line)',
                            rows: 6,
                            value: links.map(function(l) { return (l.text || '') + ' | ' + (l.url || '#'); }).join('\n'),
                            onChange: function(v) {
                                var lines = v.split('\n').filter(function(s) { return s.trim().length > 0; });
                                var parsed = lines.map(function(line) {
                                    var parts = line.split('|');
                                    return {
                                        text: parts[0] ? parts[0].trim() : '',
                                        url: parts[1] ? parts[1].trim() : '#'
                                    };
                                });
                                props.setAttributes({ links: parsed });
                            }
                        })
                    )
                ),
                _el('div', bp,
                    _el('div', { style: { fontFamily: "'Sora', sans-serif", fontSize: '11px', fontWeight: 700, textTransform: 'uppercase', letterSpacing: '1.5px', color: BRAND, marginBottom: '12px' } }, '📚 ' + (a.label || 'Related Reading')),
                    _el('ul', { style: { margin: 0, padding: 0, listStyle: 'none', display: 'flex', flexDirection: 'column', gap: '8px' } },
                        links.map(function(link, i) {
                            return _el('li', { key: i, style: { fontSize: '14px', color: '#0E1220', fontWeight: 600, display: 'flex', justifyContent: 'space-between', padding: '6px 10px', background: '#fff', border: '1px solid #e2e8f0', borderRadius: '6px' } },
                                _el('span', null, link.text || 'Article Title'),
                                _el('span', { style: { color: BRAND } }, '→')
                            );
                        })
                    )
                )
            );
        },
        save: function() { return null; }
    });

    /* ═══════════════════════════════════════════════════
       17. DEPRECATED BLOCKS COMPATIBILITY REGISTRATION
       ═══════════════════════════════════════════════ */
    ['lead-paragraph', 'highlight', 'risk-banner', 'section-header', 'author-bio'].forEach(function(slug) {
        _blocks.registerBlockType('foundnxt/' + slug, {
            edit: function(props) {
                var a = props.attributes;
                var bp = _useBlockProps({
                    style: {
                        background: '#f1f5f9',
                        border: '1px dashed #94a3b8',
                        borderRadius: '8px',
                        padding: '16px',
                        margin: '12px 0',
                        fontSize: '13px',
                        color: '#64748B'
                    }
                });
                return _el('div', bp,
                    _el('div', { style: { fontWeight: 700, color: '#0E1220', marginBottom: '4px' } }, 'FoundNXT ' + slug + ' (Legacy Block)'),
                    _el('div', null, a.content || a.heading || a.name || 'Legacy content preserved for existing posts.')
                );
            },
            save: function() { return null; }
        });
    });

})();
