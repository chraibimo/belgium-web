<?php
/**
 * Mickey, the living TV (tv.js). Included by the home page and the channel list.
 * Expects $tvChannels (list of ['id' => scene, 'name', 'meta', 'href']) and
 * $tvMode: '' = the home set (fixed channels, auto-surf), 'guide' = the channel
 * list page drives it through window.MTV.tv (search results, upcoming events).
 */
if (!defined('PRIMO_APP')) exit;
$tvMode = $tvMode ?? '';
?>
      <div class="tv" id="tv" data-channels="<?= e(json_attr($tvChannels)) ?>"<?= $tvMode !== '' ? ' data-tv-mode="' . e($tvMode) . '"' : '' ?>>
        <svg class="tv__antenna" viewBox="0 0 200 90" aria-hidden="true">
          <g stroke="#000" stroke-width="5" stroke-linecap="round"><line x1="100" y1="80" x2="38" y2="8"/><line x1="100" y1="80" x2="172" y2="16"/></g>
          <circle cx="38" cy="8" r="7" fill="#fee2a8" stroke="#000" stroke-width="3"/><circle cx="172" cy="16" r="7" fill="#fee2a8" stroke="#000" stroke-width="3"/>
          <path d="M68 92 Q100 56 132 92 Z" fill="#3a2a1f" stroke="#000" stroke-width="4"/>
        </svg>
        <span class="tv__hint" aria-hidden="true"><?= e(content('js')['tv_hint']) ?></span>
        <svg class="tv__arm tv__arm--l" viewBox="0 0 120 160" aria-hidden="true"><path d="M112 40 C70 30 30 60 30 100 C30 118 42 130 58 130" fill="none" stroke="#000" stroke-width="14" stroke-linecap="round"/><path d="M112 40 C70 30 30 60 30 100 C30 118 42 130 58 130" fill="none" stroke="#fee2a8" stroke-width="7" stroke-linecap="round"/><ellipse cx="60" cy="132" rx="22" ry="18" fill="#fff" stroke="#000" stroke-width="5"/><path d="M50 122v18M60 118v22M70 122v18" fill="none" stroke="#000" stroke-width="3" stroke-linecap="round"/></svg>
        <svg class="tv__arm tv__arm--r" viewBox="0 0 120 160" aria-hidden="true"><path d="M8 40 C50 30 90 60 90 100 C90 118 78 130 62 130" fill="none" stroke="#000" stroke-width="14" stroke-linecap="round"/><path d="M8 40 C50 30 90 60 90 100 C90 118 78 130 62 130" fill="none" stroke="#fee2a8" stroke-width="7" stroke-linecap="round"/><ellipse cx="60" cy="132" rx="22" ry="18" fill="#fff" stroke="#000" stroke-width="5"/><rect x="48" y="90" width="24" height="52" rx="6" fill="#222" stroke="#000" stroke-width="4"/><circle cx="60" cy="100" r="4" fill="#c4252d"/><path d="M54 112h12M54 120h12M54 128h12" stroke="#fee2a8" stroke-width="2.5" stroke-linecap="round"/></svg>
        <div class="tv__top" data-tv-top title="<?= e(content('js')['tv_hint']) ?>"></div>
        <div class="tv__body">
          <div class="tv__bezel">
            <div class="tv__screen" data-tv-screen>
              <canvas class="tv__static" width="160" height="120" data-tv-static></canvas>
              <span class="tv__osd" data-tv-osd>CH 00</span>
              <span class="tv__label" data-tv-label hidden></span>
              <div class="tv__off" aria-hidden="true"></div>
            </div>
          </div>
          <div class="tv__panel">
            <button type="button" class="tv__knob" data-tv-knob="1" aria-label="<?= e(t('tv.next')) ?>"></button>
            <button type="button" class="tv__knob tv__knob--sm" data-tv-knob="-1" aria-label="<?= e(t('tv.prev')) ?>"></button>
            <span class="tv__grille" aria-hidden="true"></span>
            <button type="button" class="tv__power" data-tv-power aria-label="<?= e(t('tv.power')) ?>"></button>
          </div>
          <span class="tv__name" aria-hidden="true">MICKEY</span>
        </div>
        <div class="tv__legs" aria-hidden="true"><span class="tv__leg tv__leg--l"></span><span class="tv__leg tv__leg--r"></span></div>
      </div>
