<?php
/**
 * FoundNXT Block Patterns
 * Ready-to-insert editorial patterns for rapid copy-paste authoring.
 *
 * @package FoundNXT
 */
defined('ABSPATH') || exit;

add_action('init', function () {
    if (!function_exists('register_block_pattern_category') || !function_exists('register_block_pattern')) {
        return;
    }

    // Register FoundNXT category
    register_block_pattern_category(
        'foundnxt',
        ['label' => __('FoundNXT Editorial', 'foundnxt')]
    );

    // 1. Key Takeaways Card Pattern
    register_block_pattern(
        'foundnxt/takeaway-card',
        [
            'title'       => __('Key Takeaways Card', 'foundnxt'),
            'description' => __('Highlighted summary box with core insights and actionable takeaways.', 'foundnxt'),
            'categories'  => ['foundnxt'],
            'content'     => '<!-- wp:foundnxt/takeaway {"title":"Key Takeaways","content":"1. Focus on scalable distribution channels before aggressive scaling.\n2. Maintain at least 18-24 months of runway in unpredictable markets.\n3. Keep unit economics positive from seed stage."} /-->',
        ]
    );

    // 2. Pros & Cons Analysis Pattern
    register_block_pattern(
        'foundnxt/pros-cons-grid',
        [
            'title'       => __('Pros & Cons Comparison', 'foundnxt'),
            'description' => __('Balanced side-by-side evaluation grid with checkmarks and crosses.', 'foundnxt'),
            'categories'  => ['foundnxt'],
            'content'     => '<!-- wp:foundnxt/pros-cons {"prosTitle":"Advantages","consTitle":"Trade-offs \u0026 Risks","pros":["Higher enterprise contract value (ACV)","Defensible proprietary technology moat","Clear path to profitability"],"cons":["Longer sales cycles (6-9 months)","Higher customer acquisition upfront costs","Requires dedicated implementation support"]} /-->',
        ]
    );

    // 3. SEO FAQ Accordion Pattern
    register_block_pattern(
        'foundnxt/faq-accordion',
        [
            'title'       => __('SEO FAQ Accordion (FAQPage Schema)', 'foundnxt'),
            'description' => __('Collapsible FAQ accordion with built-in FAQPage Schema.org microdata.', 'foundnxt'),
            'categories'  => ['foundnxt'],
            'content'     => '<!-- wp:foundnxt/faq {"title":"Frequently Asked Questions","items":[{"q":"What is the standard pre-seed dilution in 2026?","a":"Typically between 10% and 15% depending on founder track record and market validation."},{"q":"When should a startup build an in-house advisory board?","a":"Once product-market fit is proven and enterprise go-to-market scaling begins."},{"q":"How do VCs evaluate customer retention metrics?","a":"Net Revenue Retention (NRR) above 115% is considered top-quartile for B2B SaaS."}]} /-->',
        ]
    );

    // 4. Ecosystem Stats Grid Pattern
    register_block_pattern(
        'foundnxt/stats-grid',
        [
            'title'       => __('Key Metrics Grid (3 Cards)', 'foundnxt'),
            'description' => __('3-card KPI grid displaying numbers, trend badges, and source citations.', 'foundnxt'),
            'categories'  => ['foundnxt'],
            'content'     => '<!-- wp:foundnxt/stats {"stats":[{"val":"$4.2B","label":"Capital Deployed","trend":"+28% YoY","trendDirection":"up"},{"val":"340+","label":"Funded Ventures","trend":"Seed to Series B","trendDirection":"neutral"},{"val":"91.4%","label":"Founder Retention","trend":"Top decile","trendDirection":"up"}],"source":"FoundNXT State of Startups Report 2026"} /-->',
        ]
    );

    // 5. Step-by-Step Framework Pattern
    register_block_pattern(
        'foundnxt/steps-framework',
        [
            'title'       => __('Step-by-Step Framework', 'foundnxt'),
            'description' => __('Numbered procedural guide with gradient badges and connecting tracks.', 'foundnxt'),
            'categories'  => ['foundnxt'],
            'content'     => '<!-- wp:foundnxt/steps {"steps":[{"title":"Validate Problem Urgency","desc":"Conduct 25+ structured customer discovery interviews with target buyers."},{"title":"Build the Minimal Lovable Product (MLP)","desc":"Focus on the single core workflow that delivers 10x value over legacy solutions."},{"title":"Launch Early Pilot Cohort","desc":"Secure 3-5 reference customers with signed letters of intent or pilot agreements."},{"title":"Refine Pricing \u0026 Unit Economics","desc":"Establish repeatable gross margins above 70% before deploying paid acquisition."}]} /-->',
        ]
    );

    // 6. Founder Verdict Scorecard Pattern
    register_block_pattern(
        'foundnxt/verdict-scorecard',
        [
            'title'       => __('Verdict & Scorecard', 'foundnxt'),
            'description' => __('Product or strategy scorecard with numerical rating, badge, and final take.', 'foundnxt'),
            'categories'  => ['foundnxt'],
            'content'     => '<!-- wp:foundnxt/verdict {"product":"Enterprise AI Stack 2026","score":"9.4","badge":"Highly Recommended","take":"A standout platform that balances developer velocity with enterprise-grade data security. Essential for technical founders."} /-->',
        ]
    );

    // 7. High-Converting Newsletter CTA Box Pattern
    register_block_pattern(
        'foundnxt/cta-newsletter-box',
        [
            'title'       => __('High-Converting Lead CTA Box', 'foundnxt'),
            'description' => __('Obsidian card with brand orange accents and call-to-action button.', 'foundnxt'),
            'categories'  => ['foundnxt'],
            'content'     => '<!-- wp:foundnxt/cta-box {"tag":"Free Executive Briefing","title":"Get the Weekly FoundNXT Startup Playbook","desc":"Join 25,000+ founders, operators, and venture investors receiving our actionable teardowns every Tuesday.","btnText":"Subscribe Free \u2192","btnUrl":"#newsletter"} /-->',
        ]
    );

    // 8. Callout Note Pattern (Tip & Insight)
    register_block_pattern(
        'foundnxt/note-tip',
        [
            'title'       => __('Callout Note — Pro Tip', 'foundnxt'),
            'description' => __('Highlighted tip callout card for strategic advice and warnings.', 'foundnxt'),
            'categories'  => ['foundnxt'],
            'content'     => '<!-- wp:foundnxt/note {"type":"tip","title":"Founder Pro Tip","text":"Always lock down IP assignments and vesting agreements before accepting external capital."} /-->',
        ]
    );

    // 9. Big Stat Spotlight Pattern
    register_block_pattern(
        'foundnxt/big-stat-spotlight',
        [
            'title'       => __('Big Stat Spotlight', 'foundnxt'),
            'description' => __('Hero-style giant statistic banner with contextual explanation and badge.', 'foundnxt'),
            'categories'  => ['foundnxt'],
            'content'     => '<!-- wp:foundnxt/big-number {"figure":"\u20b98.4L Cr","context":"Total early-stage venture funding deployed in emerging tech ecosystems over the past 24 months.","label":"Market Milestone","badge":"+145% YoY","source":"FoundNXT Venture Database"} /-->',
        ]
    );

    // 10. Related Reading Inline Hub Pattern
    register_block_pattern(
        'foundnxt/related-reading-hub',
        [
            'title'       => __('Related Reading Deep Dives', 'foundnxt'),
            'description' => __('Mid-article internal linking module to boost dwell time and SEO authority.', 'foundnxt'),
            'categories'  => ['foundnxt'],
            'content'     => '<!-- wp:foundnxt/related-reading {"label":"Essential Startup Reading","links":[{"text":"How Top Founders Structure Their First Cap Table","url":"#"},{"text":"The 5 Financial Metrics Indian VCs Prioritize in 2026","url":"#"},{"text":"Bootstrapping vs Venture Capital: The Real Math","url":"#"}]} /-->',
        ]
    );
});
