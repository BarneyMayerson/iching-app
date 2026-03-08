<!DOCTYPE html>
<html lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Divination Report - {{ $reading->uuid }}</title>
  <style>
    @page {
      margin: 2cm;
    }

    body {
      font-family: 'DejaVu Sans', serif;
      color: #1e293b;
      line-height: 1.5;
      margin: 0;
      padding: 0;
    }

    .header {
      text-align: center;
      margin-bottom: 30px;
      border-bottom: 1px solid #e2e8f0;
      padding-bottom: 20px;
    }

    .header h1 {
      font-size: 24px;
      color: #0f172a;
      margin-bottom: 5px;
    }

    .header p {
      font-size: 12px;
      color: #64748b;
      margin: 0;
    }

    .question-section {
      margin-bottom: 40px;
      text-align: center;
    }

    .question-label {
      font-size: 10px;
      text-transform: uppercase;
      tracking: 2px;
      color: #94a3b8;
      font-weight: bold;
    }

    .question-text {
      font-size: 18px;
      font-style: italic;
      color: #b45309;
      margin-top: 10px;
      padding: 0 20px;
    }

    .hexagram-container {
      width: 100%;
      margin-bottom: 40px;
    }

    .hexagram-visual {
      width: 140px;
      margin: 0 auto;
    }

    .line-row {
      height: 12px;
      margin-bottom: 6px;
      width: 100%;
      clear: both;
    }

    .line-yang {
      width: 100%;
      height: 100%;
      background-color: #1e293b;
    }

    .line-yin-left {
      width: 43%;
      height: 100%;
      background-color: #1e293b;
      float: left;
    }

    .line-yin-right {
      width: 43%;
      height: 100%;
      background-color: #1e293b;
      float: right;
    }

    .changing-bg {
      background-color: #f59e0b !important;
    }

    .hex-info {
      text-align: center;
      margin-bottom: 30px;
    }

    .hex-number {
      font-size: 14px;
      font-weight: bold;
      color: #64748b;
    }

    .hex-name {
      font-size: 22px;
      font-weight: bold;
      color: #0f172a;
      margin: 5px 0;
    }

    .trigrams-wrapper {
      margin: 20px auto;
      width: 90%;
      border-top: 1px solid #f1f5f9;
      padding-top: 20px;
    }

    .trigrams-table {
      width: 100%;
      border-collapse: collapse;
    }

    .trigram-cell {
      width: 50%;
      padding: 10px;
      vertical-align: top;
    }

    .trigram-card {
      background-color: #f8fafc;
      border-radius: 12px;
      padding: 15px;
      text-align: left;
    }

    .trigram-symbol {
      font-size: 28px;
      color: #b45309;
      float: left;
      margin-right: 15px;
      line-height: 1;
    }

    .trigram-label {
      font-size: 9px;
      text-transform: uppercase;
      letter-spacing: 1px;
      color: #94a3b8;
      font-weight: bold;
    }

    .trigram-element {
      font-size: 14px;
      font-weight: bold;
      color: #334155;
      font-family: 'DejaVu Sans', serif;
    }

    .trigram-attribute {
      font-size: 11px;
      font-style: italic;
      color: #64748b;
      margin-top: 4px;
    }

    .clear {
      clear: both;
    }

    .lines-list {
      margin-top: 30px;
    }

    .line-item {
      margin-bottom: 15px;
      padding: 15px;
      border-radius: 8px;
      border-left: 4px solid #f1f5f9;
      background-color: #f8fafc;
    }

    .line-item.active {
      border-left-color: #f59e0b;
      background-color: #fffbeb;
    }

    .line-pos {
      font-size: 11px;
      font-weight: bold;
      color: #64748b;
      margin-bottom: 5px;
    }

    .line-meaning {
      font-size: 13px;
      color: #334155;
    }

    .sage-path {
      position: relative;
      margin-top: 30px;
      padding-left: 30px;
    }

    .path-line {
      position: absolute;
      left: -16px;
      top: -16px;
      bottom: 56px;
      width: 2px;
      background: #e2e8f0;
    }

    .path-node {
      position: absolute;
      left: -25px;
      top: 20px;
      width: 10px;
      height: 10px;
      border-radius: 50%;
      background: #cbd5e1;
      border: 3px solid #fff;
    }

    .active .path-node {
      background: #f59e0b;
      box-shadow: 0 0 0 2px #fef3c7;
    }

    .line-position-tab {
      position: absolute;
      left: -65px;
      top: 15px;
      width: 30px;
      text-align: right;
      font-size: 10px;
      font-weight: bold;
      color: #94a3b8;
    }

    .active .line-position-tab {
      color: #b45309;
    }

    .oracle-conclusion {
      margin-top: 40px;
      padding: 20px;
      border-radius: 12px;
      background-color: #f8fafc;
      border: 1px solid #e2e8f0;
      text-align: center;
    }

    .conclusion-title {
      font-size: 10px;
      text-transform: uppercase;
      letter-spacing: 2px;
      color: #94a3b8;
      margin-bottom: 10px;
      font-weight: bold;
    }

    .conclusion-text {
      font-family: 'DejaVu Sans', serif;
      font-style: italic;
      font-size: 14px;
      color: #475569;
    }

    .conclusion-icon {
      font-size: 20px;
      margin-bottom: 10px;
      display: block;
    }

    .page-break {
      page-break-after: always;
    }

    .footer {
      position: fixed;
      bottom: 0;
      width: 100%;
      text-align: center;
      font-size: 10px;
      color: #94a3b8;
      padding-bottom: 10px;
    }
  </style>
</head>

<body>

  <div class="header">
    <h1>I-Ching Oracle</h1>
    <p>Consultation Record: {{ $reading->created_at->format('M d, Y @ H:i') }}</p>
  </div>

  <div class="question-section">
    <div class="question-label">The Inquiry</div>
    <div class="question-text">"{{ $reading->question }}"</div>
  </div>

  <div class="hexagram-container">
    <div class="hexagram-visual">
      @php
        $lines = str_split(strrev($reading->binary)); // Разворачиваем, чтобы линии шли снизу вверх
      @endphp
      @foreach ($lines as $index => $bit)
        @php
          $pos = 5 - $index;
          $isChanging = in_array($pos, $changing_lines);
        @endphp
        <div class="line-row">
          @if ($bit == '1')
            <div class="line-yang {{ $isChanging ? 'changing-bg' : '' }}"></div>
          @else
            <div class="line-yin-left {{ $isChanging ? 'changing-bg' : '' }}"></div>
            <div class="line-yin-right {{ $isChanging ? 'changing-bg' : '' }}"></div>
          @endif
        </div>
      @endforeach
    </div>

    <div class="hex-info">
      <div class="hex-number">Hexagram {{ $reading->hexagram->number }}</div>
      <div class="hex-name">{{ $reading->hexagram->character }} {{ $reading->hexagram->names[0] }}</div>

      @if (count($reading->hexagram->names) > 1)
        <div style="font-size: 14px; color: #64748b; margin-bottom: 10px;">
          {{ implode(' / ', array_slice($reading->hexagram->names, 1)) }}
        </div>
      @endif

      <div style="font-size: 14px; line-height: 1.6; color: #475569; padding: 0 30px; margin-bottom: 20px;">
        {{ $reading->hexagram->judgment }}
      </div>

      <div class="trigrams-wrapper">
        <table class="trigrams-table">
          <tr>
            <td class="trigram-cell">
              <div class="trigram-card">
                <div class="trigram-symbol">{{ $reading->hexagram->upperTrigram->character }}</div>
                <div class="trigram-content">
                  <div class="trigram-label">Above (Outer)</div>
                  <div class="trigram-element">
                    {{ $reading->hexagram->upperTrigram->images[0] }}
                    @if (isset($reading->hexagram->upperTrigram->images[1]))
                      <span style="color: #94a3b8;">/ {{ $reading->hexagram->upperTrigram->images[1] }}</span>
                    @endif
                  </div>
                  <div class="trigram-attribute">{{ $reading->hexagram->upperTrigram->attribute }}</div>
                </div>
                <div class="clear"></div>
              </div>
            </td>
            <td class="trigram-cell">
              <div class="trigram-card">
                <div class="trigram-symbol">{{ $reading->hexagram->lowerTrigram->character }}</div>
                <div class="trigram-content">
                  <div class="trigram-label">Below (Inner)</div>
                  <div class="trigram-element">
                    {{ $reading->hexagram->lowerTrigram->images[0] }}
                    @if (isset($reading->hexagram->lowerTrigram->images[1]))
                      <span style="color: #94a3b8;">/ {{ $reading->hexagram->lowerTrigram->images[1] }}</span>
                    @endif
                  </div>
                  <div class="trigram-attribute">{{ $reading->hexagram->lowerTrigram->attribute }}</div>
                </div>
                <div class="clear"></div>
              </div>
            </td>
          </tr>
        </table>
      </div>
    </div>
  </div>

  <div class="page-break"></div>
  <div class="lines-list">
    <h3 style="font-size: 16px; border-bottom: 1px solid #e2e8f0; padding-bottom: 10px; margin-bottom: 20px;">
      The Path of the Sage: Line Guidance
    </h3>

    <div class="sage-path">
      <div class="path-line"></div>

      @foreach ($reading->hexagram->hexagramLines as $line)
        @php $isChanging = in_array($line->position - 1, $changing_lines); @endphp

        <div class="line-item {{ $isChanging ? 'active' : '' }}" style="position: relative; margin-left: 15px;">
          <div class="line-position-tab">L{{ $line->position }}</div>
          <div class="path-node"></div>

          <div class="line-pos">
            Line {{ $line->position }} —
            <span style="{{ $isChanging ? 'color: #b45309;' : '' }}">
              {{ $isChanging ? 'Changing (Active Insight)' : 'Stable' }}
            </span>
          </div>
          <div class="line-meaning" style="font-style: {{ $isChanging ? 'normal' : 'italic' }};">
            {{ $line->meaning }}
          </div>
        </div>
      @endforeach
    </div>
  </div>

  @if ($reading->secondaryHexagram)
    <div class="page-break"></div>
    <div class="header">
      <h1>The Transformation</h1>
      <p>Future Tendency & Resulting State</p>
    </div>

    <div class="hexagram-container">
      <div class="hexagram-visual">
        @php
          $secLines = str_split(strrev($reading->secondaryHexagram->binary)); // Разворачиваем, чтобы линии шли снизу вверх
        @endphp
        @foreach ($secLines as $bit)
          <div class="line-row">
            @if ($bit == '1')
              <div class="line-yang"></div>
            @else
              <div class="line-yin-left"></div>
              <div class="line-yin-right"></div>
            @endif
          </div>
        @endforeach
      </div>
      <div class="hex-info">
        <div class="hex-number">
          Hexagram {{ $reading->secondaryHexagram->number }}
        </div>
        <div class="hex-name">
          {{ $reading->secondaryHexagram->character }} {{ $reading->secondaryHexagram->names[0] }}
        </div>
        <div style="margin-top: 15px; font-size: 13px; color: #475569; padding: 0 40px;">
          {{ $reading->secondaryHexagram->judgment }}
        </div>
      </div>
    </div>

    <div class="trigrams-wrapper">
      <table class="trigrams-table">
        <tr>
          <td class="trigram-cell">
            <div class="trigram-card">
              <div class="trigram-symbol">{{ $reading->secondaryHexagram->upperTrigram->character }}</div>
              <div class="trigram-content">
                <div class="trigram-label">Above (Outer)</div>
                <div class="trigram-element">
                  {{ $reading->secondaryHexagram->upperTrigram->images[0] }}
                  @if (isset($reading->secondaryHexagram->upperTrigram->images[1]))
                    <span style="color: #94a3b8;">/ {{ $reading->secondaryHexagram->upperTrigram->images[1] }}</span>
                  @endif
                </div>
                <div class="trigram-attribute">{{ $reading->secondaryHexagram->upperTrigram->attribute }}</div>
              </div>
              <div class="clear"></div>
            </div>
          </td>
          <td class="trigram-cell">
            <div class="trigram-card">
              <div class="trigram-symbol">{{ $reading->secondaryHexagram->lowerTrigram->character }}</div>
              <div class="trigram-content">
                <div class="trigram-label">Below (Inner)</div>
                <div class="trigram-element">
                  {{ $reading->secondaryHexagram->lowerTrigram->images[0] }}
                  @if (isset($reading->secondaryHexagram->lowerTrigram->images[1]))
                    <span style="color: #94a3b8;">/ {{ $reading->secondaryHexagram->lowerTrigram->images[1] }}</span>
                  @endif
                </div>
                <div class="trigram-attribute">{{ $reading->secondaryHexagram->lowerTrigram->attribute }}</div>
              </div>
              <div class="clear"></div>
            </div>
          </td>
        </tr>
      </table>
    </div>
  @endif

  <div class="oracle-conclusion">
    <div class="conclusion-title">The Oracle's Final Reflection</div>

    @if (empty($changing_lines))
      <div class="conclusion-text">
        <span class="conclusion-icon">◈</span>
        The pattern is firm and the energy is concentrated.
        This moment calls for presence rather than movement.
        The essence of <strong>{{ $reading->hexagram->names[0] }}</strong> is your complete answer;
        seek not to change the circumstances, but to align yourself with them.
      </div>
    @else
      <div class="conclusion-text">
        <span class="conclusion-icon">✦</span>
        You are in a state of dynamic flow. The core of your inquiry lies within the
        {{ count($changing_lines) }} points of tension identified on your path.
        As <strong>{{ $reading->hexagram->names[0] }}</strong> dissolves into
        <strong>{{ $reading->secondaryHexagram->names[0] }}</strong>, remember that the only
        constant is change itself. Move with the current, not against it.
      </div>
    @endif
  </div>

  <div class="footer">
    Generated by Gemini I-Ching Cabinet • {{ $reading->uuid }}
  </div>

</body>

</html>
