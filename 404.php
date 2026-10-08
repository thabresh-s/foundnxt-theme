<?php get_header(); ?>

<div class="fnx-404">

  <!-- ── Animated canvas background ── -->
  <canvas id="fnx-404-canvas" aria-hidden="true"></canvas>

  <div class="container">
    <div class="fnx-404-inner">

      <!-- Animated 404 number -->
      <div class="e404-num" aria-hidden="true">
        <span class="e404-4 e404-4a">4</span>
        <span class="e404-0">
          <svg class="e404-coin" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
            <circle cx="50" cy="50" r="46" fill="none" stroke="currentColor" stroke-width="4"/>
            <circle cx="50" cy="50" r="34" fill="none" stroke="currentColor" stroke-width="2" opacity=".4"/>
            <!-- ₹ symbol -->
            <text x="50" y="64" text-anchor="middle" font-size="36" font-weight="900" fill="currentColor" font-family="sans-serif">₹</text>
          </svg>
        </span>
        <span class="e404-4 e404-4b">4</span>
      </div>

      <!-- Headline -->
      <h1 class="e404-title"><?php _e('This page took a wrong turn.', 'foundnxt'); ?></h1>
      <p class="e404-desc"><?php _e('The article or page you\'re looking for has moved, been removed, or never existed. Let\'s get you back on track.', 'foundnxt'); ?></p>

      <!-- Search — working, styled -->
      <div class="e404-search-wrap">
        <form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>" class="e404-search-form">
          <div class="e404-search-inner">
            <svg class="e404-search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
            <input type="search" name="s" class="e404-search-input"
              placeholder="<?php esc_attr_e('Search articles, topics, guides…', 'foundnxt'); ?>"
              value="<?php echo esc_attr(get_search_query()); ?>"
              autocomplete="off" autofocus>
            <button type="submit" class="e404-search-btn">
              <?php _e('Search', 'foundnxt'); ?>
            </button>
          </div>
        </form>
      </div>

      <!-- Quick links -->
      <div class="e404-actions">
        <a href="<?php echo esc_url(home_url('/')); ?>" class="btn-primary e404-btn"><?php _e('← Back to Home', 'foundnxt'); ?></a>
        <a href="<?php echo esc_url(home_url('/articles/')); ?>" class="btn-outline e404-btn"><?php _e('Browse Articles', 'foundnxt'); ?></a>
      </div>

      <!-- Topic chips -->
      <div class="e404-topics">
        <span class="e404-topics-label"><?php _e('Popular topics:', 'foundnxt'); ?></span>
        <?php
        $cats = get_categories(['orderby' => 'count', 'order' => 'DESC', 'number' => 6, 'hide_empty' => true]);
        foreach ($cats as $cat):
        ?>
          <a href="<?php echo esc_url(get_category_link($cat->term_id)); ?>" class="e404-chip">
            <?php echo esc_html($cat->name); ?>
          </a>
        <?php endforeach; ?>
      </div>

    </div>
  </div>
</div>

<style>
/* ── Layout ── */
.fnx-404 {
  min-height: 88vh;
  display: flex;
  align-items: center;
  position: relative;
  overflow: hidden;
  padding: 60px 0;
}
#fnx-404-canvas {
  position: absolute;
  inset: 0;
  width: 100%; height: 100%;
  pointer-events: none;
  z-index: 0;
  opacity: .55;
}
.fnx-404 .container { position: relative; z-index: 1; }
.fnx-404-inner { text-align: center; max-width: 640px; margin: 0 auto; }

/* ── 404 number ── */
.e404-num {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 4px;
  margin-bottom: 24px;
  line-height: 1;
}
.e404-4 {
  font-family: 'Source Serif 4', Georgia, serif;
  font-size: clamp(80px, 14vw, 160px);
  font-weight: 900;
  color: var(--fnx-primary);
  opacity: .18;
  display: block;
  animation: e404-bounce 2.4s ease-in-out infinite;
}
.e404-4b { animation-delay: .4s; }
.e404-coin {
  width: clamp(70px, 12vw, 140px);
  height: clamp(70px, 12vw, 140px);
  color: var(--fnx-primary);
  opacity: .22;
  animation: e404-spin 8s linear infinite;
  display: block;
}

/* ── Headline ── */
.e404-title {
  font-family: 'Source Serif 4', Georgia, serif;
  font-size: clamp(22px, 3vw, 36px);
  font-weight: 800;
  color: var(--fnx-ink);
  line-height: 1.25;
  margin: 0 0 12px;
  animation: e404-fade-up .7s ease .1s both;
}
.e404-desc {
  font-size: clamp(14px, 1.6vw, 16px);
  color: var(--fnx-muted);
  line-height: 1.7;
  margin: 0 0 32px;
  animation: e404-fade-up .7s ease .2s both;
}

/* ── Search ── */
.e404-search-wrap {
  margin-bottom: 24px;
  animation: e404-fade-up .7s ease .3s both;
}
.e404-search-form { width: 100%; }
.e404-search-inner {
  display: flex;
  align-items: center;
  background: var(--fnx-card);
  border: 1.5px solid var(--fnx-border);
  border-radius: 100px;
  padding: 6px 6px 6px 18px;
  gap: 10px;
  transition: border-color .2s, box-shadow .2s;
}
.e404-search-inner:focus-within {
  border-color: var(--fnx-primary);
  box-shadow: 0 0 0 3px rgba(79,70,229,.12);
}
.e404-search-icon {
  width: 18px; height: 18px;
  color: var(--fnx-muted);
  flex-shrink: 0;
}
.e404-search-input {
  flex: 1;
  border: none;
  background: transparent;
  font-size: 15px;
  color: var(--fnx-ink);
  outline: none;
  font-family: 'DM Sans', sans-serif;
  padding: 6px 0;
  min-width: 0;
}
.e404-search-input::placeholder { color: var(--fnx-muted); }
.e404-search-btn {
  background: var(--fnx-primary);
  color: #fff;
  border: none;
  border-radius: 100px;
  padding: 10px 22px;
  font-size: 13px;
  font-weight: 700;
  font-family: 'DM Sans', sans-serif;
  cursor: pointer;
  flex-shrink: 0;
  transition: background .2s, transform .15s;
}
.e404-search-btn:hover {
  background: var(--fnx-primary-dark, #3730a3);
  transform: scale(1.03);
}

/* ── CTA buttons ── */
.e404-actions {
  display: flex;
  gap: 10px;
  justify-content: center;
  flex-wrap: wrap;
  margin-bottom: 28px;
  animation: e404-fade-up .7s ease .4s both;
}
.e404-btn { padding: .65em 1.6em; font-size: 14px; }

/* ── Topic chips ── */
.e404-topics {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  flex-wrap: wrap;
  animation: e404-fade-up .7s ease .5s both;
}
.e404-topics-label {
  font-size: 12px;
  color: var(--fnx-muted);
  font-weight: 600;
  white-space: nowrap;
}
.e404-chip {
  font-size: 12px;
  font-weight: 500;
  color: var(--fnx-body);
  background: var(--fnx-surface);
  border: 1px solid var(--fnx-border);
  border-radius: 100px;
  padding: 4px 12px;
  text-decoration: none;
  transition: all .2s;
}
.e404-chip:hover {
  background: var(--fnx-primary-light);
  border-color: var(--fnx-primary-border);
  color: var(--fnx-primary);
}

/* ── Keyframes ── */
@keyframes e404-bounce {
  0%, 100% { transform: translateY(0); }
  50%       { transform: translateY(-10px); }
}
@keyframes e404-spin {
  from { transform: rotateY(0deg); }
  to   { transform: rotateY(360deg); }
}
@keyframes e404-fade-up {
  from { opacity: 0; transform: translateY(16px); }
  to   { opacity: 1; transform: translateY(0); }
}

@media(max-width:540px) {
  .fnx-404 { min-height: 80vh; padding: 40px 0; }
  .e404-search-inner { padding: 5px 5px 5px 14px; }
  .e404-search-btn { padding: 8px 16px; }
  .e404-actions { flex-direction: column; align-items: center; }
  .e404-btn { width: 100%; max-width: 260px; justify-content: center; }
}
</style>

<script>
(function(){
  var canvas = document.getElementById('fnx-404-canvas');
  if (!canvas) return;
  var ctx = canvas.getContext('2d');
  var W, H;
  var green = getComputedStyle(document.documentElement).getPropertyValue('--fnx-primary').trim() || '#4f46e5';

  function resize() {
    var wrap = canvas.parentElement;
    W = canvas.width  = wrap.offsetWidth;
    H = canvas.height = wrap.offsetHeight;
  }
  resize();
  window.addEventListener('resize', resize);

  /* Particles */
  var PX = Array.from({length: 22}, function() { return {
    x: Math.random(), y: Math.random(),
    r: 1 + Math.random() * 2.5,
    vx: (Math.random()-.5) * .0002,
    vy: -(Math.random() * .00025 + .0001),
    a: .05 + Math.random() * .15,
    pa: Math.random() * Math.PI * 2,
    ps: .008 + Math.random() * .008
  }; });

  /* Falling ₹ symbols */
  var RUPEES = Array.from({length: 7}, function() { return {
    x: Math.random(),
    y: Math.random(),
    size: 14 + Math.random() * 18,
    vy: .0004 + Math.random() * .0006,
    a: .04 + Math.random() * .08,
    rot: Math.random() * Math.PI * 2,
    vr: (Math.random()-.5) * .012
  }; });

  /* Trend line */
  var LINE_PTS = [.05,.75, .12,.60, .18,.68, .25,.50, .32,.55, .40,.38, .48,.42, .56,.28, .65,.32, .72,.18, .80,.22, .88,.10, .95,.14];
  var lineProgress = 0;

  function frame() {
    ctx.clearRect(0,0,W,H);
    lineProgress = Math.min(1, lineProgress + .004);

    /* Gradient background tint */
    var grd = ctx.createRadialGradient(W*.5, H*.3, 0, W*.5, H*.3, W*.6);
    grd.addColorStop(0, 'rgba(79,70,229,.03)');
    grd.addColorStop(1, 'rgba(79,70,229,0)');
    ctx.fillStyle = grd;
    ctx.fillRect(0,0,W,H);

    /* Trend line */
    var count = Math.floor(lineProgress * (LINE_PTS.length/2 - 1));
    if (count > 0) {
      ctx.beginPath();
      ctx.moveTo(LINE_PTS[0]*W, LINE_PTS[1]*H);
      for (var i=1; i<=count; i++) ctx.lineTo(LINE_PTS[i*2]*W, LINE_PTS[i*2+1]*H);
      if (count < LINE_PTS.length/2-1) {
        var frac = lineProgress*(LINE_PTS.length/2-1)-count;
        var x1=LINE_PTS[count*2]*W, y1=LINE_PTS[count*2+1]*H;
        var x2=LINE_PTS[(count+1)*2]*W, y2=LINE_PTS[(count+1)*2+1]*H;
        ctx.lineTo(x1+(x2-x1)*frac, y1+(y2-y1)*frac);
      }
      ctx.strokeStyle = 'rgba(79,70,229,.14)';
      ctx.lineWidth = 2.5;
      ctx.lineJoin = 'round';
      ctx.stroke();
      ctx.strokeStyle = 'rgba(79,70,229,.05)';
      ctx.lineWidth = 10;
      ctx.stroke();
    }

    /* Particles */
    PX.forEach(function(p) {
      p.x += p.vx; p.y += p.vy; p.pa += p.ps;
      if (p.y < -.02) { p.y = 1.02; p.x = Math.random(); }
      if (p.x < -.02 || p.x > 1.02) p.x = Math.random();
      var a = (.5 + .5*Math.sin(p.pa)) * p.a;
      ctx.beginPath();
      ctx.arc(p.x*W, p.y*H, p.r, 0, Math.PI*2);
      ctx.fillStyle = 'rgba(79,70,229,'+a+')';
      ctx.fill();
    });

    /* Falling ₹ */
    RUPEES.forEach(function(r) {
      r.y += r.vy; r.rot += r.vr;
      if (r.y > 1.08) { r.y = -.08; r.x = Math.random(); }
      ctx.save();
      ctx.translate(r.x*W, r.y*H);
      ctx.rotate(r.rot);
      ctx.fillStyle = 'rgba(79,70,229,'+r.a+')';
      ctx.font = 'bold '+r.size+'px sans-serif';
      ctx.textAlign = 'center';
      ctx.textBaseline = 'middle';
      ctx.fillText('₹', 0, 0);
      ctx.restore();
    });

    requestAnimationFrame(frame);
  }
  frame();
})();
</script>

<?php get_footer(); ?>
