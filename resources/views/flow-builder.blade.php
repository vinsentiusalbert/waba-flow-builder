<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>WABA Flow Builder</title>
    <style>
        :root {
            --bg: #f4f7fa;
            --panel: #ffffff;
            --line: #dfe6ec;
            --line-soft: #edf1f5;
            --text: #111827;
            --muted: #6f8090;
            --brand: #e31b23;
            --brand-dark: #b9151b;
            --navy: #001a41;
            --dark: #1f2937;
            --green: #24c460;
            --shadow: 0 18px 42px rgba(0, 26, 65, .08);
        }

        * { box-sizing: border-box; }
        body {
            margin: 0;
            background: var(--bg);
            color: var(--text);
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        button, input, textarea, select { font: inherit; }
        button { cursor: pointer; }

        .dashboard-shell {
            min-height: 100vh;
            display: grid;
            grid-template-columns: 398px minmax(0, 1fr);
            background: #eef2f7;
        }

        .sidebar {
            position: relative;
            min-height: 100vh;
            overflow: hidden;
            background:
                radial-gradient(circle at -8% 14%, rgba(76, 134, 255, .22) 0 18%, transparent 19%),
                radial-gradient(circle at -15% 55%, rgba(76, 134, 255, .2) 0 24%, transparent 25%),
                linear-gradient(180deg, #092551 0%, #0d2a59 100%);
            color: #fff;
            padding: 34px 32px 28px;
        }

        .sidebar::before,
        .sidebar::after {
            content: "";
            position: absolute;
            inset: -12% auto auto -52%;
            width: 160%;
            height: 160%;
            pointer-events: none;
            opacity: .26;
        }

        .sidebar::before {
            background:
                repeating-radial-gradient(circle at 0 0, transparent 0 10px, rgba(118, 171, 255, .45) 11px 12px);
            transform: rotate(14deg);
        }

        .sidebar::after {
            inset: auto auto -38% -58%;
            background:
                repeating-radial-gradient(circle at 0 100%, transparent 0 12px, rgba(118, 171, 255, .24) 13px 14px);
            transform: rotate(-12deg);
        }

        .sidebar-inner {
            position: relative;
            z-index: 1;
            display: grid;
            align-content: start;
            gap: 28px;
            min-height: 100%;
        }

        .sidebar-brand {
            font-size: 48px;
            line-height: 1;
            font-weight: 900;
            letter-spacing: -.03em;
            color: #fff;
            margin: 28px 0 18px;
        }

        .sidebar-cta {
            width: 100%;
            min-height: 58px;
            border: 0;
            border-radius: 8px;
            background: #fff;
            color: #ff1831;
            font-size: 22px;
            font-weight: 800;
        }

        .sidebar-nav {
            display: grid;
            gap: 10px;
        }

        .sidebar-group {
            display: grid;
            gap: 6px;
        }

        .sidebar-item {
            min-height: 58px;
            display: grid;
            grid-template-columns: 44px 1fr 18px;
            align-items: center;
            gap: 14px;
            border-radius: 14px;
            padding: 0 18px;
            color: rgba(255, 255, 255, .58);
            font-size: 22px;
            font-weight: 700;
            text-decoration: none;
        }

        .sidebar-item svg {
            width: 32px;
            height: 32px;
            stroke: currentColor;
            fill: none;
            stroke-width: 1.8;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .sidebar-item.active {
            color: #fff;
            background: rgba(255, 255, 255, .05);
        }

        .sidebar-item.selected {
            position: relative;
            color: #fff;
            background: rgba(255, 255, 255, .05);
        }

        .sidebar-item.selected::after {
            content: "";
            position: absolute;
            top: 50%;
            right: -32px;
            width: 10px;
            height: 62px;
            border-radius: 999px 0 0 999px;
            background: #ff9e2c;
            transform: translateY(-50%);
        }

        .sidebar-caret {
            font-size: 20px;
            text-align: center;
        }

        .sidebar-submenu {
            display: grid;
            gap: 4px;
            padding-left: 58px;
            margin-top: -2px;
        }

        .sidebar-subitem {
            position: relative;
            min-height: 42px;
            display: flex;
            align-items: center;
            border-radius: 10px;
            padding: 0 16px;
            color: rgba(255, 255, 255, .64);
            font-size: 18px;
            font-weight: 700;
            text-decoration: none;
        }

        .sidebar-subitem.active {
            color: #fff;
            background: rgba(255, 255, 255, .08);
        }

        .sidebar-subitem.active::before {
            content: "";
            position: absolute;
            left: -16px;
            top: 50%;
            width: 6px;
            height: 28px;
            border-radius: 999px;
            background: #ff9e2c;
            transform: translateY(-50%);
        }

        .content-shell {
            min-width: 0;
            display: grid;
            grid-template-rows: auto minmax(0, 1fr);
            background: #f7f9fc;
        }

        .topbar {
            position: sticky;
            top: 0;
            z-index: 10;
            min-height: 124px;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 48px;
            padding: 24px 44px;
            background: #fff;
            box-shadow: 0 6px 24px rgba(0, 26, 65, .08);
        }

        .topbar-help,
        .topbar-user {
            display: flex;
            align-items: center;
            gap: 14px;
            color: var(--navy);
            font-size: 23px;
            font-weight: 800;
        }

        .topbar-icon {
            width: 36px;
            height: 36px;
            display: inline-grid;
            place-items: center;
            border: 2px solid rgba(17, 24, 39, .35);
            border-radius: 999px;
            color: rgba(17, 24, 39, .45);
            font-size: 23px;
            font-weight: 700;
        }

        .content-area {
            min-width: 0;
            padding: 46px 44px 28px;
        }

        .page-header {
            display: grid;
            gap: 18px;
            margin-bottom: 18px;
        }

        .breadcrumbs {
            color: #526578;
            font-size: 18px;
            font-weight: 600;
        }

        .breadcrumbs b {
            color: var(--navy);
        }

        .page-title {
            margin: 0;
            color: var(--navy);
            font-size: 58px;
            line-height: 1.05;
            letter-spacing: -.03em;
        }

        .page-tabs {
            display: flex;
            gap: 26px;
            border-bottom: 1px solid rgba(17, 24, 39, .16);
        }

        .page-tab {
            position: relative;
            display: inline-flex;
            align-items: center;
            min-height: 54px;
            color: var(--navy);
            font-size: 25px;
            font-weight: 800;
        }

        .page-tab::after {
            content: "";
            position: absolute;
            left: 0;
            right: 0;
            bottom: -1px;
            height: 4px;
            border-radius: 999px;
            background: var(--brand);
        }

        .builder-actions {
            display: none;
            align-items: center;
            justify-content: flex-end;
            flex-wrap: wrap;
            gap: 12px;
            margin: 18px 0 18px;
        }

        .app[data-setup-complete="true"] .builder-actions { display: flex; }
        .app[data-section="ads"] .builder-actions { display: none; }

        .workspace {
            display: none;
        }

        .workspace.active {
            display: block;
        }

        .ads-page {
            display: grid;
            gap: 30px;
            padding: 8px 0 32px;
        }

        .ads-steps {
            position: relative;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 26px;
            padding: 0 8px;
        }

        .ads-steps::before {
            content: "";
            position: absolute;
            left: 48px;
            right: 48px;
            top: 18px;
            height: 2px;
            background: #d7dde6;
        }

        .ads-step {
            position: relative;
            z-index: 1;
            display: grid;
            justify-items: center;
            gap: 14px;
            text-align: center;
            color: #3f4b5c;
            font-size: 16px;
            line-height: 1.35;
        }

        .ads-step-badge {
            width: 36px;
            height: 36px;
            display: grid;
            place-items: center;
            border-radius: 999px;
            background: #505866;
            color: #fff;
            font-weight: 800;
            font-size: 15px;
        }

        .ads-step.active .ads-step-badge {
            background: var(--brand);
        }

        .ads-step.done .ads-step-badge {
            background: var(--navy);
            font-size: 18px;
        }

        .ads-step.active strong,
        .ads-step.done strong {
            color: var(--navy);
        }

        .ads-stage {
            display: none;
        }

        .ads-stage.active {
            display: grid;
            gap: 30px;
        }

        .ads-card {
            border: 1px solid var(--line-soft);
            border-radius: 14px;
            background: #fff;
            padding: 22px;
        }

        .ads-note {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            color: #526578;
            font-size: 16px;
        }

        .ads-note strong {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            color: var(--navy);
        }

        .ads-note-icon {
            width: 28px;
            height: 28px;
            display: inline-grid;
            place-items: center;
            border-radius: 999px;
            background: #f0f4f8;
            color: var(--navy);
            font-weight: 900;
        }

        .ads-form {
            display: grid;
            gap: 22px;
        }

        .ads-form h2 {
            margin: 0;
            color: var(--navy);
            font-size: 28px;
        }

        .ads-field {
            display: grid;
            gap: 10px;
        }

        .ads-field label {
            color: var(--navy);
            font-size: 16px;
            font-weight: 700;
        }

        .ads-select {
            width: 100%;
            min-height: 58px;
            border: 1px solid rgba(17, 24, 39, .2);
            border-radius: 8px;
            padding: 0 18px;
            background: #fff;
            color: #758392;
            font-size: 16px;
        }

        .ads-radio-group {
            display: flex;
            flex-wrap: wrap;
            gap: 24px;
            margin-top: 6px;
        }

        .ads-radio {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            color: var(--navy);
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
        }

        .ads-radio input {
            width: 18px;
            height: 18px;
            margin: 0;
            accent-color: var(--brand);
        }

        .ads-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-top: 12px;
        }

        .ads-actions.left {
            justify-content: flex-start;
        }

        .ads-draft-btn {
            min-height: 58px;
            display: inline-flex;
            align-items: center;
            gap: 12px;
            border: 1px solid rgba(0, 26, 65, .25);
            border-radius: 8px;
            padding: 0 18px;
            background: #fff;
            color: var(--navy);
            font-size: 18px;
            font-weight: 800;
        }

        .ads-prev-btn {
            min-height: 58px;
            border: 1px solid rgba(0, 26, 65, .25);
            border-radius: 8px;
            padding: 0 18px;
            background: #fff;
            color: var(--navy);
            font-size: 18px;
            font-weight: 800;
        }

        .ads-section-title {
            margin: 0;
            color: var(--navy);
            font-size: 28px;
        }

        .ads-copy {
            margin: 0;
            max-width: 1120px;
            color: #526578;
            font-size: 16px;
            line-height: 1.55;
        }

        .ads-location-row,
        .ads-profile-row {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 24px;
        }

        .ads-location-btn {
            min-width: 422px;
            min-height: 56px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            border: 1px solid rgba(0, 26, 65, .35);
            border-radius: 8px;
            background: #fff;
            color: var(--navy);
            font-size: 18px;
            font-weight: 800;
        }

        .ads-inline-link {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            color: var(--navy);
            font-size: 16px;
            font-weight: 700;
        }

        .ads-inline-link b {
            width: 24px;
            height: 24px;
            display: inline-grid;
            place-items: center;
            border-radius: 4px;
            background: var(--navy);
            color: #fff;
            font-size: 20px;
            line-height: 1;
        }

        .ads-profile-row .ads-field {
            min-width: 308px;
        }

        .ads-profile-more {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            color: var(--navy);
            font-size: 18px;
            font-weight: 700;
            text-decoration: none;
        }

        .ads-validity {
            display: grid;
            gap: 18px;
            padding-top: 8px;
            border-top: 1px solid rgba(17, 24, 39, .12);
        }

        .ads-validity h3 {
            margin: 0;
            color: var(--navy);
            font-size: 26px;
        }

        .ads-validity p {
            margin: 0;
            color: #8a96a4;
            font-size: 14px;
        }

        .ads-validity-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
        }

        .ads-session-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
            align-items: end;
        }

        .ads-session-hint {
            display: grid;
            gap: 10px;
        }

        .ads-session-hint label {
            color: var(--navy);
            font-size: 16px;
            font-weight: 700;
        }

        .ads-session-hint span {
            min-height: 58px;
            display: flex;
            align-items: center;
            border: 1px solid rgba(17, 24, 39, .12);
            border-radius: 8px;
            padding: 0 18px;
            background: #f8fafc;
            color: #526578;
            font-size: 16px;
        }

        .ads-timeout {
            display: grid;
            gap: 22px;
            padding-top: 10px;
            border-top: 1px solid rgba(17, 24, 39, .12);
        }

        .ads-timeout h3 {
            margin: 0;
            color: var(--navy);
            font-size: 26px;
        }

        .ads-timeout p {
            margin: 0;
            color: #8a96a4;
            font-size: 14px;
        }

        .ads-timeout-grid,
        .ads-timeout-message {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
        }

        .ads-input-shell {
            position: relative;
        }

        .ads-input-shell .suffix {
            top: 50%;
            right: 16px;
            transform: translateY(-50%);
        }

        .ads-message-card {
            border: 1px solid rgba(17, 24, 39, .08);
            border-radius: 18px;
            background: #fff;
            padding: 16px;
        }

        .ads-message-card > span {
            display: block;
            margin-bottom: 12px;
            color: #8a96a4;
            font-size: 14px;
            font-weight: 600;
        }

        .ads-toolbar {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
            min-height: 48px;
            border: 1px solid var(--line);
            border-radius: 10px 10px 0 0;
            padding: 8px 12px;
        }

        .ads-toolbar select {
            min-width: 124px;
            border: 1px solid var(--line);
            border-radius: 8px;
            padding: 8px 10px;
            background: #fff;
        }

        .ads-tool {
            border: 0;
            background: transparent;
            color: var(--navy);
            font-size: 16px;
            font-weight: 800;
        }

        .ads-timeout-textarea {
            width: 100%;
            min-height: 140px;
            border: 1px solid var(--line);
            border-top: 0;
            border-radius: 0 0 10px 10px;
            padding: 16px;
            resize: vertical;
            color: var(--text);
            line-height: 1.5;
        }

        .ads-timeout-preview {
            min-height: 214px;
            border-radius: 18px;
            padding: 18px;
            background-color: #eee8df;
            background-image:
                radial-gradient(circle at 16px 18px, rgba(120, 110, 100, .08) 0 5px, transparent 6px),
                radial-gradient(circle at 68px 48px, rgba(120, 110, 100, .08) 0 10px, transparent 11px),
                radial-gradient(circle at 145px 24px, rgba(120, 110, 100, .08) 0 7px, transparent 8px);
            background-size: 120px 86px;
        }

        .ads-delivery {
            display: grid;
            gap: 26px;
        }

        .ads-recipient-row,
        .ads-schedule-grid {
            display: grid;
            grid-template-columns: 320px 1fr;
            gap: 26px;
            align-items: start;
        }

        .ads-estimate {
            display: grid;
            gap: 8px;
            padding-top: 10px;
        }

        .ads-estimate small {
            color: #526578;
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .ads-estimate strong {
            color: #46576b;
            font-size: 20px;
            font-weight: 900;
        }

        .ads-warning {
            color: var(--brand);
            font-size: 14px;
        }

        .ads-policy {
            color: #526578;
            font-size: 14px;
            line-height: 1.55;
        }

        .ads-schedule-grid {
            grid-template-columns: 1fr 1fr;
        }

        .ads-method {
            display: grid;
            gap: 8px;
        }

        .ads-method label {
            color: #7a8796;
            font-size: 16px;
        }

        .ads-method strong {
            color: var(--navy);
            font-size: 20px;
        }

        .ads-test-section {
            display: grid;
            gap: 16px;
        }

        .ads-test-copy {
            max-width: 760px;
            color: #526578;
            font-size: 14px;
            line-height: 1.55;
        }

        .ads-test-strong {
            color: var(--navy);
            font-size: 14px;
            font-weight: 800;
        }

        .ads-consent {
            max-width: 1100px;
            display: flex;
            align-items: flex-start;
            gap: 14px;
            color: #526578;
            font-size: 14px;
            line-height: 1.55;
        }

        .ads-consent input {
            width: 24px;
            height: 24px;
            margin-top: 2px;
            accent-color: var(--brand);
        }

        .ads-consent a {
            color: var(--navy);
            font-weight: 800;
        }

        .ads-review {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 402px;
            gap: 26px;
            align-items: start;
        }

        .ads-review-main {
            display: grid;
            gap: 26px;
        }

        .ads-review-title {
            margin: 0;
            color: var(--navy);
            font-size: 52px;
            line-height: 1.05;
        }

        .ads-review-summary {
            display: grid;
            gap: 10px;
            padding-bottom: 20px;
            border-bottom: 1px solid rgba(17, 24, 39, .12);
        }

        .ads-review-summary label {
            color: #526578;
            font-size: 16px;
        }

        .ads-review-summary strong {
            color: #46576b;
            font-size: 18px;
        }

        .ads-review-link {
            justify-self: end;
            color: var(--navy);
            font-size: 16px;
            font-weight: 700;
            text-decoration: none;
        }

        .ads-review-list {
            display: grid;
            border-top: 1px solid rgba(17, 24, 39, .12);
        }

        .ads-review-item {
            min-height: 76px;
            display: grid;
            grid-template-columns: auto 1fr auto auto;
            align-items: center;
            gap: 18px;
            border-bottom: 1px solid rgba(17, 24, 39, .12);
            padding: 0 4px;
        }

        .ads-review-icon {
            width: 32px;
            height: 32px;
            display: grid;
            place-items: center;
            border: 1px solid rgba(227, 27, 35, .5);
            border-radius: 8px;
            color: var(--brand);
            font-size: 18px;
        }

        .ads-review-item strong {
            color: var(--navy);
            font-size: 18px;
        }

        .ads-review-item a,
        .ads-review-item button {
            border: 0;
            background: transparent;
            color: var(--navy);
            font-size: 16px;
            font-weight: 700;
        }

        .ads-cost-card {
            border-radius: 18px;
            background: #fff;
            box-shadow: 0 18px 42px rgba(0, 26, 65, .08);
            overflow: hidden;
        }

        .ads-cost-card::before {
            content: "";
            display: block;
            height: 10px;
            background: var(--navy);
        }

        .ads-cost-body {
            display: grid;
            gap: 20px;
            padding: 28px 34px 34px;
        }

        .ads-cost-body h3 {
            margin: 0;
            color: var(--navy);
            font-size: 28px;
        }

        .ads-cost-section {
            display: grid;
            gap: 12px;
            padding-bottom: 18px;
            border-bottom: 1px solid rgba(17, 24, 39, .12);
        }

        .ads-cost-section:last-child {
            border-bottom: 0;
            padding-bottom: 0;
        }

        .ads-cost-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            color: var(--navy);
            font-size: 14px;
            font-weight: 800;
            text-transform: uppercase;
        }

        .ads-cost-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            color: #526578;
            font-size: 16px;
        }

        .ads-cost-row strong {
            color: #46576b;
            font-size: 18px;
        }

        .ads-cost-note {
            color: #526578;
            font-size: 14px;
            line-height: 1.5;
        }

        .ads-cost-total {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            color: var(--navy);
            font-size: 18px;
            font-weight: 800;
        }

        .ads-cost-total strong {
            color: #46576b;
            font-size: 18px;
        }

        .ads-cost-danger {
            color: var(--brand);
            font-size: 14px;
            line-height: 1.45;
        }

        .ads-topup-btn {
            min-height: 56px;
            border: 0;
            border-radius: 10px;
            background: var(--brand);
            color: #fff;
            font-size: 18px;
            font-weight: 800;
        }

        .ads-next-btn {
            min-width: 214px;
            min-height: 58px;
            border: 0;
            border-radius: 8px;
            background: var(--brand);
            color: #fff;
            font-size: 18px;
            font-weight: 800;
            box-shadow: 0 10px 24px rgba(227, 27, 35, .18);
        }

        .ads-next-btn:hover {
            background: var(--brand-dark);
        }

        .ads-modal-backdrop {
            position: fixed;
            inset: 0;
            z-index: 26;
            background: rgba(17, 24, 39, .58);
            opacity: 0;
            pointer-events: none;
            transition: opacity .2s ease;
        }

        .ads-modal {
            position: fixed;
            top: 50%;
            left: 50%;
            z-index: 27;
            width: min(100% - 24px, 602px);
            border-radius: 18px;
            background: #fff;
            padding: 48px;
            box-shadow: 0 24px 60px rgba(0, 26, 65, .18);
            transform: translate(-50%, -48%);
            opacity: 0;
            pointer-events: none;
            transition: opacity .2s ease, transform .2s ease;
        }

        .ads-modal.open,
        .ads-modal-backdrop.open {
            opacity: 1;
            pointer-events: auto;
        }

        .ads-modal.open {
            transform: translate(-50%, -50%);
        }

        .ads-modal h2 {
            margin: 0 0 34px;
            color: var(--navy);
            font-size: 34px;
            line-height: 1.2;
        }

        .ads-modal-actions {
            display: grid;
            grid-template-columns: 1fr 1.2fr;
            gap: 20px;
            width: min(100%, 380px);
            margin: 44px 0 0 auto;
        }

        .ads-modal-actions .btn {
            min-height: 58px;
            font-size: 18px;
        }

        .btn {
            min-height: 44px;
            border: 1px solid var(--line);
            border-radius: 8px;
            padding: 0 16px;
            background: white;
            color: var(--text);
            font-weight: 800;
        }

        .btn.dark {
            border-color: var(--brand);
            background: var(--brand);
            color: white;
        }

        .btn.danger {
            border-color: var(--brand);
            color: var(--brand);
        }

        .page { min-height: calc(100vh - 96px); }

        .app[data-setup-complete="false"] .builder {
            pointer-events: none;
        }

        .setup-backdrop {
            position: fixed;
            inset: 0;
            z-index: 24;
            background: rgba(0, 26, 65, .2);
            opacity: 0;
            pointer-events: none;
            transition: opacity .2s ease;
        }

        .setup-modal {
            position: fixed;
            top: 50%;
            left: 50%;
            z-index: 25;
            width: min(100% - 24px, 602px);
            border-radius: 18px;
            background: #fff;
            padding: 48px;
            box-shadow: 0 24px 60px rgba(0, 26, 65, .16);
            transform: translate(-50%, -48%);
            opacity: 0;
            pointer-events: none;
            transition: opacity .2s ease, transform .2s ease;
        }

        .setup-modal.open,
        .setup-backdrop.open {
            opacity: 1;
            pointer-events: auto;
        }

        .setup-modal.open {
            transform: translate(-50%, -50%);
        }

        .setup-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 18px;
            margin-bottom: 34px;
        }

        .setup-head h2 {
            margin: 0;
            color: var(--navy);
            font-size: 29px;
            line-height: 1.2;
        }

        .setup-close {
            width: 42px;
            height: 42px;
            border: 0;
            border-radius: 999px;
            background: transparent;
            color: rgba(17, 24, 39, .45);
            font-size: 38px;
            line-height: 1;
            padding: 0;
        }

        .setup-form {
            display: grid;
            gap: 20px;
        }

        .setup-input,
        .setup-select-trigger {
            width: 100%;
            min-height: 78px;
            border: 1px solid rgba(17, 24, 39, .28);
            border-radius: 10px;
            padding: 0 24px;
            background: #fff;
            color: var(--text);
            font-size: 18px;
            outline: none;
            transition: border-color .2s ease, box-shadow .2s ease;
        }

        .setup-input::placeholder,
        .setup-select-trigger {
            color: rgba(17, 24, 39, .48);
        }

        .setup-input:focus,
        .setup-select-trigger:focus {
            border-color: rgba(227, 27, 35, .75);
            box-shadow: 0 0 0 4px rgba(227, 27, 35, .08);
        }

        .setup-select-wrap {
            position: relative;
        }

        .setup-select-label {
            position: absolute;
            top: -10px;
            left: 18px;
            z-index: 1;
            background: #fff;
            color: #5c6470;
            padding: 0 8px;
            font-size: 14px;
            line-height: 1;
        }

        .setup-select-trigger {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            text-align: left;
            cursor: pointer;
        }

        .setup-select-trigger.placeholder {
            color: rgba(17, 24, 39, .48);
        }

        .setup-select-value {
            display: grid;
            gap: 2px;
            min-width: 0;
            flex: 1;
        }

        .setup-select-name {
            color: var(--navy);
            font-size: 18px;
            font-weight: 800;
            line-height: 1.2;
        }

        .setup-select-trigger.placeholder .setup-select-name {
            color: rgba(17, 24, 39, .48);
            font-weight: 500;
        }

        .setup-select-number {
            color: #8a8f98;
            font-size: 14px;
            line-height: 1.2;
        }

        .setup-select-trigger.placeholder .setup-select-number {
            display: none;
        }

        .setup-select-caret {
            color: #8a8f98;
            font-size: 28px;
            line-height: 1;
            transform: rotate(0deg);
            transition: transform .2s ease;
        }

        .setup-select-wrap.open .setup-select-caret {
            transform: rotate(180deg);
        }

        .setup-options {
            display: none;
            gap: 0;
            margin-top: 2px;
            border: 1px solid rgba(17, 24, 39, .28);
            border-radius: 10px;
            background: #fff;
            overflow: hidden;
        }

        .setup-select-wrap.open .setup-options {
            display: grid;
        }

        .setup-option {
            width: 100%;
            min-height: 82px;
            display: grid;
            align-content: center;
            gap: 4px;
            border: 0;
            border-top: 1px solid rgba(17, 24, 39, .08);
            background: #fff;
            padding: 18px 24px;
            text-align: left;
        }

        .setup-option:first-child {
            border-top: 0;
        }

        .setup-option:hover {
            background: #fff8f8;
        }

        .setup-option-name {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--navy);
            font-size: 18px;
            font-weight: 800;
            line-height: 1.2;
        }

        .setup-option-badge {
            color: #22c55e;
            font-size: 16px;
            line-height: 1;
        }

        .setup-option-number {
            color: #8a8f98;
            font-size: 14px;
            line-height: 1.2;
        }

        .setup-actions {
            display: grid;
            grid-template-columns: 1fr 1.35fr;
            gap: 12px;
            margin-top: 8px;
            padding-left: min(92px, 18%);
        }

        .setup-actions .btn {
            min-height: 58px;
            border-radius: 8px;
            font-size: 18px;
        }

        .setup-actions .btn.danger {
            background: #fff;
        }

        .form-page {
            max-width: 1260px;
            margin: 0 auto;
            padding: 76px 0 72px;
        }

        .title {
            margin: 0 0 38px;
            font-size: 29px;
            letter-spacing: 0;
            color: var(--navy);
        }

        .card {
            background: var(--panel);
            border: 1px solid var(--line-soft);
            border-radius: 18px;
            padding: 26px;
            box-shadow: var(--shadow);
            margin-bottom: 34px;
        }

        .card h2 {
            margin: 0 0 12px;
            font-size: 22px;
            letter-spacing: 0;
            color: var(--navy);
        }

        .card p {
            margin: 0 0 24px;
            color: #526578;
            line-height: 1.5;
        }

        .field {
            display: grid;
            gap: 12px;
        }

        .field label {
            font-weight: 800;
        }

        .input-shell {
            position: relative;
        }

        .input, .select, .textarea {
            width: 100%;
            min-height: 64px;
            border: 1px solid var(--line);
            border-radius: 8px;
            padding: 0 16px;
            background: white;
            color: var(--text);
        }

        .counter, .suffix {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--muted);
            font-weight: 700;
        }

        .trigger-grid, .two-grid, .message-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 22px;
        }

        .trigger-card {
            display: grid;
            grid-template-columns: 34px 1fr;
            gap: 14px;
            min-height: 256px;
            border: 1px solid var(--line);
            border-radius: 12px;
            padding: 26px 22px;
            background: white;
        }

        .radio {
            width: 20px;
            height: 20px;
            border: 2px solid #7b8b9a;
            border-radius: 50%;
            margin-top: 8px;
        }

        .trigger-card.active .radio {
            border-color: var(--brand);
            box-shadow: inset 0 0 0 5px white;
            background: var(--brand);
        }

        .trigger-card.active {
            border-color: rgba(227, 27, 35, .55);
            box-shadow: 0 0 0 3px rgba(227, 27, 35, .08);
        }

        .trigger-card strong {
            display: block;
            margin-bottom: 6px;
            font-size: 18px;
        }

        .trigger-card span {
            display: block;
            color: #526578;
            margin-bottom: 22px;
        }

        .example {
            min-height: 142px;
            display: grid;
            grid-template-columns: 1fr 220px;
            gap: 16px;
            border: 1px solid var(--line);
            border-radius: 8px;
            background: #f8fafc;
            padding: 16px;
            overflow: hidden;
        }

        .example h3 {
            margin: 0 0 16px;
            color: #8b9bab;
            font-size: 16px;
        }

        .example p {
            margin: 0;
            color: #8b9bab;
        }

        .phone-preview {
            min-height: 126px;
            border-radius: 16px;
            padding: 14px;
            background-color: #eee8df;
            background-image:
                radial-gradient(circle at 16px 18px, rgba(120, 110, 100, .08) 0 5px, transparent 6px),
                radial-gradient(circle at 68px 48px, rgba(120, 110, 100, .08) 0 10px, transparent 11px),
                radial-gradient(circle at 145px 24px, rgba(120, 110, 100, .08) 0 7px, transparent 8px);
            background-size: 120px 86px;
        }

        .bubble {
            max-width: 76%;
            width: fit-content;
            border-radius: 4px;
            padding: 10px 12px;
            background: white;
            color: #556575;
            font-size: 12px;
            line-height: 1.4;
            box-shadow: 0 8px 16px rgba(0,0,0,.05);
        }

        .bubble.out { margin-left: auto; background: #dbffd4; }
        .bubble time {
            display: block;
            margin-top: 6px;
            text-align: right;
            color: #8b9bab;
            font-size: 10px;
        }

        .dates {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 28px;
        }

        .editor-card {
            border: 1px solid var(--line-soft);
            border-radius: 18px;
            padding: 26px;
            background: white;
            box-shadow: 0 16px 36px rgba(23, 35, 50, .05);
        }

        .toolbar {
            display: flex;
            align-items: center;
            gap: 18px;
            min-height: 48px;
            border: 1px solid var(--line);
            border-radius: 8px 8px 0 0;
            padding: 8px 12px;
        }

        .toolbar select {
            min-width: 138px;
            border: 1px solid var(--line);
            border-radius: 7px;
            padding: 8px 10px;
            background: white;
        }

        .tool {
            border: 0;
            background: transparent;
            color: var(--dark);
            font-size: 18px;
            font-weight: 800;
        }

        .textarea {
            min-height: 210px;
            border-radius: 0 0 8px 8px;
            border-top: 0;
            padding: 18px;
            resize: vertical;
            line-height: 1.5;
        }

        .footer-card {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 18px;
        }

        .builder {
            position: relative;
            height: calc(100vh - 96px);
            overflow: auto;
            padding: 0;
            background:
                radial-gradient(circle, #cfd7df 1px, transparent 1px) 0 0 / 28px 28px,
                #fbfcfd;
            cursor: grab;
            user-select: none;
        }

        .builder.panning {
            cursor: grabbing;
        }

        .builder-stage {
            position: relative;
            min-width: 1800px;
            min-height: 1800px;
            padding: 220px 24px 320px;
        }

        .flow-shell {
            position: relative;
            width: fit-content;
            margin: 0 auto;
            transform: scale(1);
            transform-origin: top center;
        }

        .flow {
            position: relative;
            width: 460px;
            display: grid;
            justify-items: center;
        }

        .start-node {
            width: 100%;
            min-height: 64px;
            display: grid;
            place-items: center;
            border-radius: 18px;
            background: var(--brand);
            color: white;
            font-size: 25px;
            font-weight: 900;
            box-shadow: 0 14px 26px rgba(227, 27, 35, .18);
        }

        .line {
            width: 2px;
            height: 42px;
            background: #1f2937;
        }

        .response-node {
            width: 100%;
            border: 1px solid var(--line-soft);
            border-radius: 18px;
            padding: 24px;
            background: #f6f8fa;
            box-shadow: var(--shadow);
        }

        .response-node h2 {
            margin: 0 0 16px;
            font-size: 24px;
        }

        .keyword-box {
            min-height: 64px;
            display: flex;
            align-items: center;
            border-radius: 14px;
            background: white;
            padding: 0 16px;
            color: #1f2937;
            font-size: 20px;
            box-shadow: inset 0 0 0 1px var(--line-soft);
        }

        .bot-nodes {
            width: 100%;
            display: grid;
            justify-items: center;
        }

        .bot-node {
            width: 100%;
            border: 1px solid var(--line-soft);
            border-radius: 18px;
            padding: 20px;
            background: white;
            box-shadow: var(--shadow);
        }

        .bot-node[data-flow-node],
        .button-branch-node[data-branch-node] {
            cursor: pointer;
        }

        .bot-node header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 14px;
        }

        .bot-node-title {
            display: grid;
            gap: 8px;
        }

        .bot-node h2 {
            margin: 0;
            color: var(--navy);
            font-size: 20px;
            line-height: 1.25;
        }

        .node-pill {
            display: inline-flex;
            align-items: center;
            min-height: 28px;
            border-radius: 999px;
            background: rgba(227, 27, 35, .08);
            color: var(--brand);
            padding: 4px 10px;
            font-size: 12px;
            font-weight: 900;
        }

        .node-message {
            min-height: 60px;
            display: flex;
            align-items: center;
            border-radius: 12px;
            background: #f7f9fb;
            color: #526578;
            padding: 14px;
            line-height: 1.45;
        }

        .node-fallback {
            display: grid;
            gap: 6px;
            border-radius: 12px;
            background: #fff6f6;
            color: #7b2730;
            padding: 14px;
            line-height: 1.45;
            box-shadow: inset 0 0 0 1px rgba(227, 27, 35, .14);
        }

        .node-fallback strong {
            color: var(--brand);
            font-size: 12px;
            letter-spacing: .04em;
            text-transform: uppercase;
        }

        .node-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .node-button-pill {
            min-height: 38px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            background: rgba(0, 26, 65, .06);
            color: var(--navy);
            padding: 0 14px;
            font-size: 14px;
            font-weight: 700;
            box-shadow: inset 0 0 0 1px rgba(0, 26, 65, .08);
        }

        .button-branches {
            display: grid;
            gap: 14px;
            width: 100%;
            margin-top: 18px;
        }

        .button-branches.two {
            grid-template-columns: 1fr 1fr;
        }

        .button-branch {
            position: relative;
            display: grid;
            gap: 10px;
            padding-top: 22px;
        }

        .button-branch::before {
            content: "";
            position: absolute;
            top: 0;
            left: 50%;
            width: 2px;
            height: 18px;
            background: #1f2937;
            transform: translateX(-50%);
        }

        .button-branch-label {
            min-height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            justify-self: center;
            border-radius: 999px;
            background: rgba(227, 27, 35, .08);
            color: var(--brand);
            padding: 0 14px;
            font-size: 13px;
            font-weight: 800;
        }

        .button-branch-node {
            min-height: 120px;
            display: grid;
            align-content: start;
            gap: 10px;
            border-radius: 14px;
            background: white;
            color: #526578;
            padding: 14px;
            line-height: 1.45;
            box-shadow: var(--shadow);
            border: 1px solid var(--line-soft);
        }

        .button-branch-node:hover,
        .bot-node[data-flow-node]:hover {
            border-color: rgba(227, 27, 35, .3);
        }

        .button-branch-node .node-pill {
            justify-self: start;
        }

        .button-branch-title {
            color: var(--navy);
            font-size: 16px;
            font-weight: 800;
        }

        .button-branch-copy {
            min-height: 56px;
            display: flex;
            align-items: center;
            border-radius: 12px;
            background: #f7f9fb;
            padding: 12px;
            box-shadow: inset 0 0 0 1px var(--line-soft);
        }

        .branch-children {
            display: grid;
            gap: 0;
        }

        .branch-children .bot-node {
            margin-top: 0;
        }

        .branch-children .line {
            justify-self: center;
        }

        .node-content {
            display: grid;
            gap: 12px;
        }

        .node-header-preview {
            min-height: 52px;
            display: none;
            align-items: center;
            border-radius: 12px;
            background: #eef3f8;
            color: var(--navy);
            padding: 14px;
            font-weight: 700;
            line-height: 1.4;
        }

        .node-header-preview.active {
            display: flex;
        }

        .node-header-preview.image {
            justify-content: center;
            min-height: 140px;
            padding: 12px;
            overflow: hidden;
        }

        .node-header-preview.image img {
            width: 100%;
            height: 116px;
            object-fit: cover;
            border-radius: 10px;
        }

        .remove-node {
            width: 34px;
            height: 34px;
            border: 1px solid var(--line);
            border-radius: 8px;
            background: white;
            color: var(--brand);
            font-size: 20px;
            font-weight: 900;
            flex: 0 0 auto;
        }

        .add-node {
            position: relative;
            width: 100%;
        }

        .add-node.branch-add {
            margin-top: 10px;
        }

        .add-response {
            width: 100%;
            min-height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            border: 1px solid var(--line-soft);
            border-radius: 18px;
            background: #f6f8fa;
            color: var(--text);
            font-size: 22px;
            font-weight: 900;
            box-shadow: 0 10px 26px rgba(23, 35, 50, .04);
        }

        .add-response:hover {
            border-color: rgba(227, 27, 35, .45);
            background: #fff;
            color: var(--brand);
        }

        .add-node.branch-add .add-response {
            min-height: 58px;
            font-size: 18px;
            border-style: dashed;
        }

        .add-node.branch-add .plus {
            font-size: 26px;
        }

        .plus {
            font-size: 32px;
            line-height: 1;
        }

        .menu {
            position: absolute;
            right: 0;
            top: calc(100% + 10px);
            z-index: 5;
            width: 220px;
            display: none;
            grid-template-columns: 1fr;
            border-radius: 10px;
            background: white;
            box-shadow: 0 18px 42px rgba(23, 35, 50, .15);
            overflow: hidden;
        }

        .menu.open { display: grid; }
        .menu button {
            min-height: 43px;
            border: 0;
            background: white;
            text-align: left;
            padding: 0 14px;
            color: #283441;
        }

        .menu button:hover {
            background: rgba(227, 27, 35, .06);
            color: var(--brand);
        }

        .drawer-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, .2);
            opacity: 0;
            visibility: hidden;
            transition: opacity .25s ease, visibility .25s ease;
            z-index: 20;
        }

        .drawer-backdrop.open {
            opacity: 1;
            visibility: visible;
        }

        .text-drawer {
            position: fixed;
            top: 0;
            right: 0;
            width: min(460px, 100vw);
            height: 100vh;
            display: grid;
            grid-template-rows: auto 1fr auto;
            background: #fff;
            box-shadow: -24px 0 60px rgba(15, 23, 42, .18);
            transform: translateX(100%);
            transition: transform .3s ease;
            z-index: 21;
        }

        .text-drawer.open {
            transform: translateX(0);
        }

        .drawer-head,
        .drawer-foot {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 20px 24px;
            border-bottom: 1px solid var(--line-soft);
        }

        .drawer-foot {
            justify-content: flex-end;
            border-top: 1px solid var(--line-soft);
            border-bottom: 0;
        }

        .drawer-head h2 {
            margin: 0;
            color: var(--navy);
            font-size: 24px;
        }

        .drawer-close {
            width: 38px;
            height: 38px;
            border: 1px solid var(--line);
            border-radius: 10px;
            background: white;
            color: var(--navy);
            font-size: 22px;
            line-height: 1;
        }

        .drawer-body {
            overflow: auto;
            padding: 24px;
            display: grid;
            gap: 22px;
        }

        .drawer-section {
            display: grid;
            gap: 12px;
        }

        .drawer-section h3 {
            margin: 0;
            color: var(--navy);
            font-size: 18px;
        }

        .drawer-section p {
            margin: 0;
            color: var(--muted);
            line-height: 1.5;
        }

        .header-types {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .header-type {
            min-height: 54px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid var(--line);
            border-radius: 14px;
            background: white;
            color: var(--navy);
            font-weight: 800;
        }

        .header-type.active {
            border-color: rgba(227, 27, 35, .5);
            background: #fff4f4;
            color: var(--brand);
        }

        .drawer-field {
            display: grid;
            gap: 8px;
        }

        .drawer-field label {
            color: var(--navy);
            font-size: 14px;
            font-weight: 700;
        }

        .drawer-input,
        .drawer-textarea {
            width: 100%;
            border: 1px solid var(--line);
            border-radius: 14px;
            background: white;
            color: var(--text);
            padding: 14px 16px;
            line-height: 1.45;
        }

        .drawer-textarea {
            min-height: 140px;
            resize: vertical;
        }

        .drawer-field.hidden {
            display: none;
        }

        .options-editor {
            display: grid;
            gap: 12px;
        }

        .option-item {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 10px;
            align-items: center;
        }

        .option-item .drawer-input {
            min-width: 0;
        }

        .button-remove {
            width: 42px;
            height: 42px;
            border: 1px solid var(--line);
            border-radius: 12px;
            background: white;
            color: var(--brand);
            font-size: 20px;
            font-weight: 900;
        }

        .option-add {
            min-height: 44px;
            border: 1px dashed rgba(227, 27, 35, .4);
            border-radius: 14px;
            background: #fff7f7;
            color: var(--brand);
            font-weight: 800;
        }

        .zoom {
            position: absolute;
            left: 24px;
            bottom: 24px;
            display: grid;
            width: 54px;
            border: 1px solid var(--line);
            border-radius: 12px;
            background: white;
            box-shadow: 0 10px 20px rgba(17, 24, 39, .08);
            overflow: hidden;
            z-index: 3;
        }

        .zoom button {
            min-height: 40px;
            border: 0;
            border-bottom: 1px solid var(--line);
            background: white;
            color: var(--navy);
            font-size: 18px;
            font-weight: 800;
            line-height: 1;
        }

        .zoom button.active {
            background: #fff3f3;
            color: var(--brand);
        }

        .zoom button:hover {
            background: #f7f9fb;
            color: var(--brand);
        }

        .zoom button[data-zoom-action="reset"],
        .zoom button[data-zoom-action="fit"] {
            font-size: 12px;
            letter-spacing: .03em;
        }

        .zoom button:last-child { border-bottom: 0; }

        .zoom-presets {
            position: absolute;
            left: 72px;
            bottom: 40px;
            width: 74px;
            display: none;
            grid-template-columns: 1fr;
            border: 1px solid var(--line);
            border-radius: 12px;
            background: white;
            box-shadow: 0 10px 20px rgba(17, 24, 39, .08);
            overflow: hidden;
        }

        .zoom-presets.open {
            display: grid;
        }

        .zoom-presets button {
            min-height: 38px;
            border: 0;
            border-bottom: 1px solid var(--line);
            background: white;
            color: var(--navy);
            font-size: 12px;
            font-weight: 800;
            letter-spacing: .02em;
        }

        .zoom-presets button:last-child {
            border-bottom: 0;
        }

        .zoom-presets button:hover,
        .zoom-presets button.active {
            background: #fff3f3;
            color: var(--brand);
        }

        .minimap {
            position: absolute;
            right: 24px;
            bottom: 24px;
            width: 224px;
            height: 168px;
            border: 8px solid #dedede;
            border-radius: 8px;
            background: white;
            box-shadow: 0 10px 20px rgba(17, 24, 39, .08);
            overflow: hidden;
            z-index: 3;
        }

        .minimap-track {
            position: absolute;
            inset: 0;
            background:
                radial-gradient(circle, rgba(207, 215, 223, .75) 1px, transparent 1px) 0 0 / 18px 18px,
                #fff;
        }

        .minimap-content {
            position: absolute;
            left: 0;
            top: 0;
            border-radius: 8px;
            background: rgba(0, 26, 65, .12);
            box-shadow: inset 0 0 0 1px rgba(0, 26, 65, .08);
        }

        .minimap-viewport {
            position: absolute;
            border: 2px solid var(--brand);
            border-radius: 8px;
            background: rgba(227, 27, 35, .08);
            box-shadow: 0 0 0 1px rgba(255, 255, 255, .7) inset;
            pointer-events: none;
        }

        .screen { display: none; }
        .screen.active { display: block; }

        @media (max-width: 1100px) {
            .dashboard-shell {
                grid-template-columns: 1fr;
            }

            .sidebar {
                min-height: auto;
                padding: 24px 18px;
            }

            .sidebar-brand {
                margin: 0;
                font-size: 36px;
            }

            .sidebar-nav {
                grid-template-columns: 1fr 1fr;
            }

            .sidebar-item.selected::after {
                display: none;
            }

            .topbar {
                min-height: auto;
                justify-content: space-between;
                gap: 20px;
                padding: 18px;
            }

            .topbar-help,
            .topbar-user {
                font-size: 18px;
            }

            .content-area {
                padding: 28px 18px 18px;
            }

            .page-title {
                font-size: 42px;
            }

            .builder-actions {
                justify-content: stretch;
            }
        }

        @media (max-width: 760px) {
            .form-page { padding: 34px 14px; }
            .card { padding: 18px; border-radius: 12px; }
            .trigger-grid, .two-grid, .message-grid, .dates {
                grid-template-columns: 1fr;
            }

            .example { grid-template-columns: 1fr; }
            .builder-actions .btn { flex: 1 1 150px; }
            .minimap { display: none; }
            .menu {
                left: auto;
                right: 0;
                top: 74px;
            }
            .builder-stage {
                min-width: 1200px;
                min-height: 1100px;
            }

            .setup-modal {
                width: calc(100% - 24px);
                padding: 26px 20px 22px;
            }

            .setup-head h2 { font-size: 24px; }

            .setup-input,
            .setup-select-trigger {
                min-height: 64px;
                font-size: 16px;
                padding: 0 18px;
            }

            .setup-actions {
                grid-template-columns: 1fr;
                padding-left: 0;
            }
        }
    </style>
</head>
<body>
<div class="dashboard-shell">
    <aside class="sidebar" aria-label="Main navigation">
        <div class="sidebar-inner">
            <div class="sidebar-brand">MyAds</div>
            <button class="sidebar-cta" type="button" id="openAdsMenu">Buat Iklan</button>

            <nav class="sidebar-nav">
                <a class="sidebar-item active" href="#">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect></svg>
                    <span>Dashboard</span>
                    <span></span>
                </a>
                <a class="sidebar-item" href="#">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 7h16v10H4z"></path><path d="M4 10h16"></path><path d="M9 15h3"></path></svg>
                    <span>Saldo</span>
                    <span class="sidebar-caret">⌄</span>
                </a>
                <div class="sidebar-group">
                    <a class="sidebar-item selected" href="#" id="templateMenuLink">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3l8 4.5-8 4.5-8-4.5L12 3z"></path><path d="M4 12l8 4.5 8-4.5"></path><path d="M4 16.5 12 21l8-4.5"></path></svg>
                        <span>Template</span>
                        <span class="sidebar-caret">⌄</span>
                    </a>
                    <div class="sidebar-submenu" aria-label="Template submenu">
                        <a class="sidebar-subitem active" href="#" id="templateSubmenuLink">WA Interaktif</a>
                    </div>
                </div>
                <a class="sidebar-item" href="#">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M17 21v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    <span>Sender Name</span>
                    <span class="sidebar-caret">⌄</span>
                </a>
                <div class="sidebar-group">
                    <a class="sidebar-item" href="#" id="adsMenuLink">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2a7 7 0 0 1 7 7c0 5.25-7 13-7 13S5 14.25 5 9a7 7 0 0 1 7-7z"></path><circle cx="12" cy="9" r="2.5"></circle></svg>
                        <span>Iklan</span>
                        <span class="sidebar-caret">⌄</span>
                    </a>
                    <div class="sidebar-submenu" aria-label="Iklan submenu">
                        <a class="sidebar-subitem" href="#" id="adsSubmenuLink">Buat Iklan WA Business LBA</a>
                    </div>
                </div>
                <a class="sidebar-item" href="#">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 2h9l5 5v15H6z"></path><path d="M15 2v5h5"></path><path d="M9 13h6"></path><path d="M9 17h6"></path></svg>
                    <span>Laporan</span>
                    <span class="sidebar-caret">⌄</span>
                </a>
                <a class="sidebar-item" href="#">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="9"></circle><path d="M9.1 9a3 3 0 1 1 5.8 1c0 2-3 2-3 4"></path><path d="M12 17h.01"></path></svg>
                    <span>Bantuan</span>
                    <span class="sidebar-caret">⌄</span>
                </a>
            </nav>
        </div>
    </aside>

    <section class="app content-shell" id="app" data-step="0" data-setup-complete="false">
        <header class="topbar">
            <div class="topbar-help">Butuh Bantuan? <span class="topbar-icon">?</span></div>
            <div class="topbar-user">Kos Annisa <span class="topbar-icon">◯</span></div>
        </header>

        <div class="content-area">
            <div class="page-header">
                <div class="breadcrumbs" id="pageBreadcrumbs">Template / <b>WA Interaktif</b></div>
                <h1 class="page-title" id="pageTitle">WA Interaktif</h1>
                <div class="page-tabs">
                    <span class="page-tab" id="pageTabLabel">WA Interaktif</span>
                </div>
            </div>

            <div class="builder-actions">
                <button class="btn" type="button" data-action="open-setup">Edit Setup</button>
                <button class="btn danger" type="button" data-action="discard">Discard Changes</button>
                <button class="btn" type="button" data-action="draft">Save as Draft</button>
                <button class="btn dark" type="button" data-action="publish">Publish Edited Flow</button>
            </div>

            <section class="workspace active" id="templateWorkspace">
                <section class="screen active" data-screen="0">
                    <div class="builder" id="builderCanvas">
                        <div class="builder-stage" id="builderStage">
                            <div class="flow-shell" id="flowShell">
                                <div class="flow">
                                    <div class="start-node">Start</div>
                                    <div class="line"></div>
                                    <div class="response-node">
                                        <h2>User Response</h2>
                                        <div class="keyword-box" id="userKeywordPreview">Any Keyword Send</div>
                                    </div>
                                    <div class="line"></div>
                                    <div class="bot-nodes" id="botNodes"></div>
                                </div>
                            </div>
                        </div>

                        <div class="zoom" aria-label="Canvas controls">
                            <button type="button" data-zoom-action="in">+</button>
                            <button type="button" data-zoom-action="out">-</button>
                            <button type="button" data-zoom-action="preset-toggle" id="zoomPresetToggle">100%</button>
                            <button type="button" data-zoom-action="fit">FIT</button>
                            <div class="zoom-presets" id="zoomPresets">
                                <button type="button" data-zoom-preset="1">100%</button>
                                <button type="button" data-zoom-preset="0.75">75%</button>
                                <button type="button" data-zoom-preset="0.5">50%</button>
                                <button type="button" data-zoom-preset="0.25">25%</button>
                            </div>
                        </div>
                        <div class="minimap" id="minimap" aria-hidden="true">
                            <div class="minimap-track"></div>
                            <div class="minimap-content" id="minimapContent"></div>
                            <div class="minimap-viewport" id="minimapViewport"></div>
                        </div>
                    </div>
                </section>
            </section>

            <section class="workspace" id="adsWorkspace">
                <div class="ads-page">
                    <div class="ads-steps">
                        <div class="ads-step active" id="adsStepIndicator1">
                            <span class="ads-step-badge" id="adsStepBadge1">01</span>
                            <strong>Pilih Template Pesan</strong>
                        </div>
                        <div class="ads-step" id="adsStepIndicator2">
                            <span class="ads-step-badge">02</span>
                            <strong>Atur Target Penerima</strong>
                        </div>
                        <div class="ads-step" id="adsStepIndicator3">
                            <span class="ads-step-badge">03</span>
                            <span>Atur Pengiriman</span>
                        </div>
                        <div class="ads-step" id="adsStepIndicator4">
                            <span class="ads-step-badge">04</span>
                            <span>Review &amp; Pembayaran</span>
                        </div>
                    </div>

                    <div class="ads-stage active" id="adsStage1">
                        <div class="ads-card ads-note">
                            <strong><span class="ads-note-icon">i</span> Pelajari cara membuat iklan WhatsApp Business LBA</strong>
                            <button class="setup-close" type="button" aria-label="Tutup panduan">&times;</button>
                        </div>

                        <div class="ads-form">
                            <h2>Template Pesan</h2>
                            <div class="ads-radio-group" role="radiogroup" aria-label="Pilih jenis template">
                                <label class="ads-radio">
                                    <input type="radio" name="adsTemplateType" value="template_pesan" checked>
                                    <span>Template Pesan</span>
                                </label>
                                <label class="ads-radio">
                                    <input type="radio" name="adsTemplateType" value="template_flow">
                                    <span>Template Flow</span>
                                </label>
                            </div>
                            <label class="ads-field">
                                <span id="adsTemplateLabel">Pilih Template Pesan</span>
                                <select class="ads-select" id="adsTemplateSelect">
                                    <option value="">Template Pesan</option>
                                    <option value="Promo MyAds">Promo MyAds</option>
                                    <option value="Campaign Rewards">Campaign Rewards</option>
                                </select>
                            </label>
                        </div>

                        <div class="ads-actions">
                            <button class="ads-draft-btn" type="button">▤ Simpan Iklan Sebagai Draft</button>
                            <button class="ads-next-btn" type="button" id="adsStep1Next">Lanjutkan</button>
                        </div>
                    </div>

                    <div class="ads-stage" id="adsStage2">
                        <h2 class="ads-section-title">Atur Lokasi Target</h2>
                        <div class="ads-location-row">
                            <button class="ads-location-btn" type="button">＋ Tambah Lokasi</button>
                            <span class="ads-inline-link"><b>+</b> Pilih Lokasi dari Daftar</span>
                        </div>

                        <div class="ads-form">
                            <h2>Atur Target Penerima</h2>
                            <p class="ads-copy">Anda dapat membuat target lebih spesifik dengan menentukan profil dan lokasi penerima. Semakin banyak profil dan lokasi yang dipilih akan mengurangi jumlah penerima iklan Anda, sehingga menurunkan jumlah iklan yang ditampilkan pada laman situs yang dikunjungi.</p>
                            <div class="ads-profile-row">
                                <label class="ads-field">
                                    <span>Jenis Kelamin</span>
                                    <select class="ads-select">
                                        <option value="">Jenis Kelamin</option>
                                        <option value="pria">Pria</option>
                                        <option value="wanita">Wanita</option>
                                    </select>
                                </label>
                                <label class="ads-field">
                                    <span>Rentang Umur</span>
                                    <select class="ads-select">
                                        <option value="">Rentang Umur</option>
                                        <option value="18-24">18-24</option>
                                        <option value="25-34">25-34</option>
                                        <option value="35-44">35-44</option>
                                    </select>
                                </label>
                                <a class="ads-profile-more" href="#">Atur Profil Lebih Spesifik ❯</a>
                            </div>
                            <span class="ads-inline-link"><b>+</b> Pilih Profil dari Daftar</span>
                        </div>

                        <div class="ads-timeout">
                            <div>
                                <h3>Define Your Timeout</h3>
                                <p>Choose our duration option or custom setup by yourself</p>
                            </div>
                            <div class="ads-timeout-grid">
                                <label class="ads-field">
                                    <span>Choose Duration</span>
                                    <select class="ads-select" id="adsDuration">
                                        <option value="">Choose Duration</option>
                                        <option value="15">15 minutes</option>
                                        <option value="30">30 minutes</option>
                                        <option value="60">60 minutes</option>
                                    </select>
                                </label>
                            </div>

                            <div>
                                <h3>Set Your Message Timeout</h3>
                                <p>Write the default message sent when a customer doesn't reply within the set time.</p>
                            </div>
                            <div class="ads-timeout-message">
                                <div class="ads-message-card">
                                    <span>Message Content</span>
                                    <div class="ads-toolbar">
                                        <select aria-label="Paragraph style"><option>Paragraph</option></select>
                                        <button class="ads-tool" type="button">B</button>
                                        <button class="ads-tool" type="button"><i>I</i></button>
                                        <button class="ads-tool" type="button"><u>U</u></button>
                                        <button class="ads-tool" type="button"><s>S</s></button>
                                        <button class="ads-tool" type="button">1.</button>
                                        <button class="ads-tool" type="button">≡</button>
                                    </div>
                                    <textarea class="ads-timeout-textarea" id="adsTimeoutMessage" maxlength="1024" placeholder="Write something awesome, example &quot;Terima kasih telah berbicara dengan tim dukungan kami! Bila memiliki pertanyaan lain, silakan menghubungi kami kembali.&quot;"></textarea>
                                </div>
                                <div class="ads-message-card">
                                    <span>Message Preview</span>
                                    <div class="ads-timeout-preview">
                                        <div class="bubble" id="adsTimeoutPreview">Your text will appear here..<time>09:25</time></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="ads-actions">
                            <div class="ads-actions left">
                                <button class="ads-prev-btn" type="button" id="adsStep2Prev">Sebelumnya</button>
                                <button class="ads-draft-btn" type="button">▤ Simpan Iklan Sebagai Draft</button>
                            </div>
                            <button class="ads-next-btn" type="button" id="adsStep2Next">Lanjutkan</button>
                        </div>
                    </div>

                    <div class="ads-stage" id="adsStage3">
                        <div class="ads-delivery">
                            <h2 class="ads-section-title">Atur Pengiriman</h2>

                            <div class="ads-form">
                                <h2>Tentukan Jumlah Penerima yang Akan Dikirim</h2>
                                <div class="ads-recipient-row">
                                    <div class="ads-field">
                                        <input class="ads-select" type="number" id="adsRecipientCount" min="5" placeholder="Jumlah Penerima">
                                        <span class="ads-warning">Minimal 5 jumlah penerima perhari</span>
                                    </div>
                                    <div class="ads-estimate">
                                        <small>Estimasi Maksimal Penerima Potensial</small>
                                        <strong>31195-38127</strong>
                                    </div>
                                </div>
                                <div class="ads-policy">⏳ Berdasarkan policy ∞ Meta, batas maksimum kuota pengiriman Anda dalam satu hari sebesar 9.999.999 pesan. Silakan sesuaikan jadwal mulai dan akhir pengiriman Anda.</div>
                            </div>

                            <div class="ads-form">
                                <h2>Jadwal Pengiriman Pesan</h2>
                                <div class="ads-schedule-grid">
                                    <label class="ads-field">
                                        <span>Tanggal Kirim</span>
                                        <input class="ads-select" type="text" value="2 Juni 2026 - 2 Juni 2026">
                                        <span class="ads-warning">Pembuatan Iklan tidak boleh sebelum waktu sekarang (17:35 WIB)</span>
                                    </label>
                                    <label class="ads-field">
                                        <span>Jam Kirim</span>
                                        <input class="ads-select" type="text" value="17:35-17:35 WIB">
                                    </label>
                                </div>
                            </div>

                            <div class="ads-method">
                                <label>Metode Pengiriman</label>
                                <strong>Pengiriman Secepatnya</strong>
                                <p class="ads-copy">Metode pengiriman pesan secepatnya memaksimalkan pesan Anda dikirim sesuai dengan jadwal yang ditentukan. Apabila terdapat pesan yang belum terkirim pada jadwal pertama, maka pesan tersebut akan dikirim sesuai dengan jadwal selanjutnya.</p>
                            </div>

                            <div class="ads-validity">
                                <div>
                                    <h3>Set Validity Period</h3>
                                    <p>Define range time for your rule based validity</p>
                                </div>
                                <div class="ads-validity-grid">
                                    <label class="ads-field">
                                        <span>Start Date</span>
                                        <input class="ads-select" type="date" id="adsStartDate">
                                    </label>
                                    <label class="ads-field">
                                        <span>End Date</span>
                                        <input class="ads-select" type="date" id="adsEndDate">
                                    </label>
                                </div>
                                <div class="ads-session-row">
                                    <label class="ads-field">
                                        <span>Jumlah Session</span>
                                        <input class="ads-select" type="number" id="adsSessionCount" min="1" placeholder="Masukkan jumlah session">
                                    </label>
                                    <div class="ads-session-hint">
                                        <label>Rekomendasi Session</label>
                                        <span>10.000 session</span>
                                    </div>
                                </div>
                            </div>

                            <div class="ads-test-section">
                                <h2 class="ads-section-title">Tentukan Nomor Test</h2>
                                <label class="ads-field" style="max-width: 440px;">
                                    <span>Nomor Tes Iklan</span>
                                    <select class="ads-select">
                                        <option value=""></option>
                                        <option value="6281119804692">6281119804692</option>
                                    </select>
                                </label>
                                <div class="ads-test-copy">Nomor test digunakan sebagai nomor penerima pesan untuk memastikan apakah pesan berhasil terkirim. Nomor test akan menerima pesan 1 jam sebelum jadwal pengiriman. Biaya Test iklan ditanggung pelanggan.</div>
                                <div class="ads-test-strong">Nomor test yang digunakan harus terdaftar pada Whatsapp</div>
                            </div>

                            <label class="ads-consent">
                                <input type="checkbox" id="adsConsent">
                                <span>Saya menyetujui <a href="#">Syarat dan Ketentuan</a> yang berlaku di website Telkomsel MyAds. Atas setiap pesan iklan yang dibuat oleh pengguna menggunakan produk dan/atau layanan melalui portal myAds, pengguna dilarang untuk mempergunakan kata-kata, komentar, gambar atau konten apapun yang mengandung unsur SARA atau diskriminasi terhadap pihak manapun, bersifat vulgar dan ancaman, atau hal-hal lain yang dapat dianggap tidak sesuai dengan nilai dan norma sosial.</span>
                            </label>

                            <div class="ads-actions">
                                <div class="ads-actions left">
                                    <button class="ads-prev-btn" type="button" id="adsStep3Prev">Sebelumnya</button>
                                    <button class="ads-draft-btn" type="button">▤ Simpan Iklan Sebagai Draft</button>
                                </div>
                                <button class="ads-next-btn" type="button" id="adsStep3Next">Lanjutkan</button>
                            </div>
                        </div>
                    </div>

                    <div class="ads-stage" id="adsStage4">
                        <div class="ads-review">
                            <div class="ads-review-main">
                                <h2 class="ads-review-title">Review</h2>
                                <div class="ads-review-summary">
                                    <label>Judul Iklan</label>
                                    <strong id="adsReviewTitle">asd</strong>
                                    <a class="ads-review-link" href="#">✎ Ubah</a>
                                </div>

                                <div class="ads-review-list">
                                    <div class="ads-review-item">
                                        <span class="ads-review-icon">💬</span>
                                        <strong>Konten Iklan</strong>
                                        <a href="#">✎ Ubah</a>
                                        <button type="button">Tampilkan ⌄</button>
                                    </div>
                                    <div class="ads-review-item">
                                        <span class="ads-review-icon">👤</span>
                                        <strong>Profil Penerima</strong>
                                        <a href="#">✎ Ubah</a>
                                        <button type="button">Tampilkan ⌄</button>
                                    </div>
                                    <div class="ads-review-item">
                                        <span class="ads-review-icon">⏰</span>
                                        <strong>Waktu Pengiriman</strong>
                                        <a href="#">✎ Ubah</a>
                                        <button type="button">Tampilkan ⌄</button>
                                    </div>
                                </div>
                            </div>

                            <aside class="ads-cost-card">
                                <div class="ads-cost-body">
                                    <h3>Detil Biaya</h3>
                                    <div class="ads-cost-section">
                                        <div class="ads-cost-head">
                                            <span>Produk yang Dipilih</span>
                                            <span>⌃</span>
                                        </div>
                                        <div class="ads-cost-row"><span>Kategori Iklan</span><strong>WA Business</strong></div>
                                        <div class="ads-cost-row"><span>Tipe Kanal</span><strong>LBA</strong></div>
                                        <div class="ads-cost-row"><span>Harga</span><strong>Rp 1.100</strong></div>
                                        <div class="ads-cost-note">Harga iklan sebesar <strong>Rp 1.100 per pesan</strong> karena menggunakan Display Name Default.</div>
                                    </div>

                                    <div class="ads-cost-section">
                                        <div class="ads-cost-row"><span>Harga Session</span><strong>10.000 x Rp 150</strong></div>
                                        <div class="ads-cost-total"><span>Grand Total <u>Tampilkan Detil</u></span><strong>Rp 110.000.000</strong></div>
                                    </div>

                                    <div class="ads-cost-section">
                                        <div class="ads-cost-head">
                                            <span>Saldo &amp; Paket Anda</span>
                                            <span>⌃</span>
                                        </div>
                                        <div class="ads-cost-row"><span>Gunakan Paket (Tersisa 0 Pesan)</span><strong>◯</strong></div>
                                        <div class="ads-cost-row"><span>Saldo Umum</span><strong>Rp 4.376.865</strong></div>
                                    </div>

                                    <div class="ads-cost-section">
                                        <div class="ads-cost-head">
                                            <span>Pembayaran Anda Menggunakan</span>
                                            <span>⌃</span>
                                        </div>
                                        <div class="ads-cost-total"><span>Saldo Umum</span><strong style="color: var(--brand);">Rp 110.000.000</strong></div>
                                        <div class="ads-cost-danger">Saldo Anda tidak cukup. Mohon lakukan Top-Up Saldo untuk melanjutkan pembelian</div>
                                        <button class="ads-topup-btn" type="button">Top Up Saldo</button>
                                    </div>
                                </div>
                            </aside>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </section>
</div>

<div class="setup-backdrop open" id="setupBackdrop"></div>
<section class="setup-modal open" id="setupModal" aria-modal="true" role="dialog" aria-labelledby="setupTitle">
    <div class="setup-head">
        <h2 id="setupTitle">Buat WA Interaktif</h2>
        <button class="setup-close" type="button" data-setup-close>&times;</button>
    </div>
    <div class="setup-form">
        <input class="setup-input" id="flowName" maxlength="255" placeholder="Nama WA Interaktif">
        <div class="setup-select-wrap" id="wabaAccountWrap">
            <span class="setup-select-label">Akun WABA</span>
            <button class="setup-select-trigger placeholder" type="button" id="wabaAccountTrigger" aria-expanded="false">
                <span class="setup-select-value">
                    <span class="setup-select-name" id="wabaAccountName">Pilih Akun WABA</span>
                    <span class="setup-select-number" id="wabaAccountNumber"></span>
                </span>
                <span class="setup-select-caret">⌃</span>
            </button>
            <div class="setup-options" id="wabaAccountOptions">
                <button
                    class="setup-option"
                    type="button"
                    data-waba-name="Telkomsel Promo &amp; Rewards"
                    data-waba-number="6281119804692"
                >
                    <span class="setup-option-name">Telkomsel Promo &amp; Rewards <span class="setup-option-badge">✹</span></span>
                    <span class="setup-option-number">6281119804692</span>
                </button>
            </div>
        </div>
        <input class="setup-input" id="triggerKeyword" maxlength="255" placeholder="Keyword">
        <div class="setup-actions">
            <button class="btn danger" type="button" data-setup-close>Batal</button>
            <button class="btn dark" type="button" id="setupSubmit">Selanjutnya</button>
        </div>
    </div>
</section>

<div class="ads-modal-backdrop" id="adsModalBackdrop"></div>
<section class="ads-modal" id="adsModal" aria-modal="true" role="dialog" aria-labelledby="adsModalTitle">
    <h2 id="adsModalTitle">Buat Judul Iklan</h2>
    <input class="setup-input" id="adsTitleInput" maxlength="255" placeholder="Judul Iklan">
    <div class="ads-modal-actions">
        <button class="btn danger" type="button" id="adsModalCancel">Batal</button>
        <button class="btn dark" type="button" id="adsModalContinue">Lanjutkan</button>
    </div>
</section>

<div class="drawer-backdrop" id="drawerBackdrop"></div>
<aside class="text-drawer" id="textMessageDrawer" aria-hidden="true">
    <div class="drawer-head">
        <div>
            <h2 id="messageDrawerTitle">Text Message</h2>
        </div>
        <button class="drawer-close" type="button" data-drawer-close>&times;</button>
    </div>
    <div class="drawer-body">
        <section class="drawer-section">
            <h3>Header</h3>
            <p>Pilih jenis header untuk pesan ini.</p>
            <div class="header-types">
                <button class="header-type active" type="button" data-header-type="text">Text</button>
                <button class="header-type" type="button" data-header-type="image">Image</button>
            </div>
        </section>

        <section class="drawer-section">
            <div class="drawer-field" id="headerTextField">
                <label for="drawerHeaderText">Header Text</label>
                <input class="drawer-input" id="drawerHeaderText" maxlength="60" placeholder="Masukkan header text">
            </div>

            <div class="drawer-field hidden" id="headerImageField">
                <label for="drawerHeaderImage">Header Image URL</label>
                <input class="drawer-input" id="drawerHeaderImage" placeholder="https://example.com/image.jpg">
            </div>

            <div class="drawer-field">
                <label for="drawerBodyText">Body</label>
                <textarea class="drawer-textarea" id="drawerBodyText" maxlength="1024" placeholder="Tulis isi pesan di sini"></textarea>
            </div>

            <div class="drawer-field">
                <label for="drawerFallbackText">Fallback</label>
                <textarea class="drawer-textarea" id="drawerFallbackText" maxlength="1024" placeholder="Tulis pesan fallback jika dibutuhkan"></textarea>
            </div>

            <div class="drawer-field hidden" id="optionsEditorField">
                <label id="optionsEditorLabel">Buttons</label>
                <div class="options-editor" id="optionsEditorList"></div>
                <button class="option-add" type="button" id="addOptionButton">+ Button</button>
            </div>
        </section>
    </div>
    <div class="drawer-foot">
        <button class="btn" type="button" data-drawer-close>Cancel</button>
        <button class="btn dark" type="button" id="saveTextMessage">Save Message</button>
    </div>
</aside>

<script>
    const app = document.getElementById('app');
    const openAdsMenuButton = document.getElementById('openAdsMenu');
    const templateMenuLink = document.getElementById('templateMenuLink');
    const templateSubmenuLink = document.getElementById('templateSubmenuLink');
    const adsMenuLink = document.getElementById('adsMenuLink');
    const adsSubmenuLink = document.getElementById('adsSubmenuLink');
    const pageBreadcrumbs = document.getElementById('pageBreadcrumbs');
    const pageTitle = document.getElementById('pageTitle');
    const pageTabLabel = document.getElementById('pageTabLabel');
    const templateWorkspace = document.getElementById('templateWorkspace');
    const adsWorkspace = document.getElementById('adsWorkspace');
    const adsStage1 = document.getElementById('adsStage1');
    const adsStage2 = document.getElementById('adsStage2');
    const adsStage3 = document.getElementById('adsStage3');
    const adsStage4 = document.getElementById('adsStage4');
    const adsStepIndicator1 = document.getElementById('adsStepIndicator1');
    const adsStepIndicator2 = document.getElementById('adsStepIndicator2');
    const adsStepIndicator3 = document.getElementById('adsStepIndicator3');
    const adsStepIndicator4 = document.getElementById('adsStepIndicator4');
    const adsStepBadge1 = document.getElementById('adsStepBadge1');
    const adsStep1Next = document.getElementById('adsStep1Next');
    const adsStep2Prev = document.getElementById('adsStep2Prev');
    const adsStep2Next = document.getElementById('adsStep2Next');
    const adsStep3Prev = document.getElementById('adsStep3Prev');
    const adsStep3Next = document.getElementById('adsStep3Next');
    const adsReviewTitle = document.getElementById('adsReviewTitle');
    const adsTemplateLabel = document.getElementById('adsTemplateLabel');
    const adsTemplateSelect = document.getElementById('adsTemplateSelect');
    const adsTimeoutMessage = document.getElementById('adsTimeoutMessage');
    const adsTimeoutPreview = document.getElementById('adsTimeoutPreview');
    const screens = [...document.querySelectorAll('.screen')];
    const botNodes = document.getElementById('botNodes');
    const builderCanvas = document.getElementById('builderCanvas');
    const builderStage = document.getElementById('builderStage');
    const flowShell = document.getElementById('flowShell');
    const minimap = document.getElementById('minimap');
    const minimapContent = document.getElementById('minimapContent');
    const minimapViewport = document.getElementById('minimapViewport');
    const zoomPresetToggle = document.getElementById('zoomPresetToggle');
    const zoomPresets = document.getElementById('zoomPresets');
    const setupBackdrop = document.getElementById('setupBackdrop');
    const setupModal = document.getElementById('setupModal');
    const setupSubmit = document.getElementById('setupSubmit');
    const adsModalBackdrop = document.getElementById('adsModalBackdrop');
    const adsModal = document.getElementById('adsModal');
    const adsTitleInput = document.getElementById('adsTitleInput');
    const adsModalCancel = document.getElementById('adsModalCancel');
    const adsModalContinue = document.getElementById('adsModalContinue');
    const flowNameInput = document.getElementById('flowName');
    const wabaAccountWrap = document.getElementById('wabaAccountWrap');
    const wabaAccountTrigger = document.getElementById('wabaAccountTrigger');
    const wabaAccountName = document.getElementById('wabaAccountName');
    const wabaAccountNumber = document.getElementById('wabaAccountNumber');
    const wabaAccountOptions = document.getElementById('wabaAccountOptions');
    const triggerKeywordInput = document.getElementById('triggerKeyword');
    const userKeywordPreview = document.getElementById('userKeywordPreview');
    const drawerBackdrop = document.getElementById('drawerBackdrop');
    const textMessageDrawer = document.getElementById('textMessageDrawer');
    const messageDrawerTitle = document.getElementById('messageDrawerTitle');
    const headerTextField = document.getElementById('headerTextField');
    const headerImageField = document.getElementById('headerImageField');
    const drawerHeaderText = document.getElementById('drawerHeaderText');
    const drawerHeaderImage = document.getElementById('drawerHeaderImage');
    const drawerBodyText = document.getElementById('drawerBodyText');
    const drawerFallbackText = document.getElementById('drawerFallbackText');
    const optionsEditorField = document.getElementById('optionsEditorField');
    const optionsEditorLabel = document.getElementById('optionsEditorLabel');
    const optionsEditorList = document.getElementById('optionsEditorList');
    const addOptionButton = document.getElementById('addOptionButton');
    const csrf = document.querySelector('meta[name="csrf-token"]').content;
    let currentStep = 0;
    let selectedTrigger = 'Inbound';
    let selectedWabaAccount = '';
    let triggerKeyword = '';
    let isSetupComplete = false;
    let adsCurrentStep = 1;
    let flowNodes = [];
    let zoomLevel = 1;
    let hasCenteredBuilder = false;
    let isPanning = false;
    let panStartX = 0;
    let panStartY = 0;
    let panScrollLeft = 0;
    let panScrollTop = 0;
    let activeDrawerTargetId = null;
    const menuOptionsMarkup = `
        <button type="button" data-node="Text Messages">Text Messages</button>
        <button type="button" data-node="Button">Button</button>
        <button type="button" data-node="List">List</button>
    `;

    function isMessageDrawerNode(node) {
        return ['text_messages', 'button'].includes(node?.type);
    }

    function createBranchFlow(label) {
        return {
            id: `branch-${Date.now().toString(36)}-${Math.random().toString(36).slice(2, 7)}`,
            label: label || 'Button',
            title: 'Text Message',
            type: 'text_messages',
            headerType: 'text',
            headerText: '',
            headerImage: '',
            body: `Text lanjutan untuk tombol "${label || 'Button'}"`,
            fallback: '',
            childNodes: [],
        };
    }

    function syncButtonBranches(node) {
        if (!['button', 'list'].includes(node.type)) return;

        const labels = (node.options || []).slice(0, node.type === 'button' ? 2 : 5);
        const existing = node.branchFlows || [];

        node.branchFlows = labels.map((label, index) => {
            const branch = existing[index] || createBranchFlow(label);
            branch.label = label || `Button ${index + 1}`;
            if (!branch.body) {
                branch.body = `Text lanjutan untuk tombol "${branch.label}"`;
            }
            return branch;
        });
    }

    function setStep(index) {
        currentStep = Math.max(0, Math.min(0, index));
        app.dataset.step = String(currentStep);

        screens.forEach((screen) => {
            screen.classList.toggle('active', Number(screen.dataset.screen) === currentStep);
        });

        requestAnimationFrame(() => {
            if (!hasCenteredBuilder) {
                centerBuilderView();
                hasCenteredBuilder = true;
            }
            applyZoom(zoomLevel);
        });
    }

    function clamp(value, min, max) {
        return Math.min(max, Math.max(min, value));
    }

    function applyZoom(value) {
        zoomLevel = clamp(Number(value) || 1, 0.25, 1.8);
        flowShell.style.transform = `scale(${zoomLevel})`;
        syncZoomControls();
        updateMinimap();
    }

    function centerBuilderView() {
        const left = (builderStage.scrollWidth - builderCanvas.clientWidth) / 2;
        const top = Math.max(0, Math.min(
            builderStage.scrollHeight - builderCanvas.clientHeight,
            180
        ));

        builderCanvas.scrollLeft = Math.max(0, left);
        builderCanvas.scrollTop = top;
        updateMinimap();
    }

    function fitBuilderView() {
        applyZoom(1);
        centerBuilderView();
    }

    function syncZoomControls() {
        if (!zoomPresetToggle) return;

        zoomPresetToggle.textContent = `${Math.round(zoomLevel * 100)}%`;

        document.querySelectorAll('[data-zoom-preset]').forEach((button) => {
            const isActive = Math.abs(Number(button.dataset.zoomPreset) - zoomLevel) < 0.001;
            button.classList.toggle('active', isActive);
        });
    }

    function toggleZoomPresets(forceOpen) {
        if (!zoomPresets) return;

        const shouldOpen = typeof forceOpen === 'boolean'
            ? forceOpen
            : !zoomPresets.classList.contains('open');

        zoomPresets.classList.toggle('open', shouldOpen);
        zoomPresetToggle.classList.toggle('active', shouldOpen);
    }

    function updateMinimap() {
        if (!minimap || currentStep !== 0) return;

        const minimapRect = minimap.getBoundingClientRect();
        const mapWidth = minimapRect.width;
        const mapHeight = minimapRect.height;
        const stageWidth = builderStage.scrollWidth;
        const stageHeight = builderStage.scrollHeight;
        const safeStageWidth = Math.max(stageWidth, 1);
        const safeStageHeight = Math.max(stageHeight, 1);
        const scale = Math.min(mapWidth / safeStageWidth, mapHeight / safeStageHeight);
        const contentWidth = flowShell.offsetWidth * scale;
        const contentHeight = flowShell.offsetHeight * zoomLevel * scale;
        const contentLeft = (mapWidth - contentWidth) / 2;
        const contentTop = clamp(mapHeight - contentHeight - 12, 8, Math.max(8, mapHeight - contentHeight));
        const viewportWidth = clamp(builderCanvas.clientWidth * scale, 24, mapWidth);
        const viewportHeight = clamp(builderCanvas.clientHeight * scale, 24, mapHeight);
        const viewportLeft = clamp(builderCanvas.scrollLeft * scale, 0, Math.max(0, mapWidth - viewportWidth));
        const viewportTop = clamp(builderCanvas.scrollTop * scale, 0, Math.max(0, mapHeight - viewportHeight));

        minimapContent.style.width = `${contentWidth}px`;
        minimapContent.style.height = `${contentHeight}px`;
        minimapContent.style.left = `${contentLeft}px`;
        minimapContent.style.top = `${contentTop}px`;

        minimapViewport.style.width = `${viewportWidth}px`;
        minimapViewport.style.height = `${viewportHeight}px`;
        minimapViewport.style.left = `${viewportLeft}px`;
        minimapViewport.style.top = `${viewportTop}px`;
    }

    function getKeywordText() {
        return triggerKeyword.trim() || 'Any Keyword Send';
    }

    function openAdsModal() {
        adsModalBackdrop.classList.add('open');
        adsModal.classList.add('open');
        adsModal.setAttribute('aria-hidden', 'false');
        adsTitleInput.focus();
    }

    function closeAdsModal() {
        adsModalBackdrop.classList.remove('open');
        adsModal.classList.remove('open');
        adsModal.setAttribute('aria-hidden', 'true');
    }

    function syncAdsTemplateType() {
        const selectedType = document.querySelector('input[name="adsTemplateType"]:checked')?.value || 'template_pesan';
        const isFlowTemplate = selectedType === 'template_flow';
        const label = isFlowTemplate ? 'Pilih Template Flow' : 'Pilih Template Pesan';
        const placeholder = isFlowTemplate ? 'Template Flow' : 'Template Pesan';

        adsTemplateLabel.textContent = label;
        adsTemplateSelect.innerHTML = isFlowTemplate
            ? `
                <option value="">${placeholder}</option>
                <option value="Flow Sambutan">Flow Sambutan</option>
                <option value="Flow Promo">Flow Promo</option>
            `
            : `
                <option value="">${placeholder}</option>
                <option value="Promo MyAds">Promo MyAds</option>
                <option value="Campaign Rewards">Campaign Rewards</option>
            `;
    }

    function syncAdsTimeoutPreview() {
        if (!adsTimeoutMessage || !adsTimeoutPreview) return;

        const value = adsTimeoutMessage.value.trim() || 'Your text will appear here..';
        adsTimeoutPreview.innerHTML = `${escapeHtml(value)}<time>09:25</time>`;
    }

    function setAdsStep(step) {
        adsCurrentStep = Math.min(4, Math.max(1, step));
        adsStage1.classList.toggle('active', adsCurrentStep === 1);
        adsStage2.classList.toggle('active', adsCurrentStep === 2);
        adsStage3.classList.toggle('active', adsCurrentStep === 3);
        adsStage4.classList.toggle('active', adsCurrentStep === 4);
        adsStepIndicator1.classList.toggle('active', adsCurrentStep === 1);
        adsStepIndicator1.classList.toggle('done', adsCurrentStep > 1);
        adsStepIndicator2.classList.toggle('active', adsCurrentStep === 2);
        adsStepIndicator2.classList.toggle('done', adsCurrentStep > 2);
        adsStepIndicator3.classList.toggle('active', adsCurrentStep === 3);
        adsStepIndicator3.classList.toggle('done', adsCurrentStep > 3);
        adsStepIndicator4.classList.toggle('active', adsCurrentStep === 4);
        adsStepBadge1.textContent = adsCurrentStep > 1 ? '✓' : '01';
    }

    function setActiveSidebarSection(section) {
        const isAdsSection = section === 'ads';
        app.dataset.section = isAdsSection ? 'ads' : 'template';

        templateMenuLink.classList.toggle('selected', !isAdsSection);
        templateSubmenuLink.classList.toggle('active', !isAdsSection);
        adsMenuLink.classList.toggle('selected', isAdsSection);
        adsSubmenuLink.classList.toggle('active', isAdsSection);
        templateWorkspace.classList.toggle('active', !isAdsSection);
        adsWorkspace.classList.toggle('active', isAdsSection);

        if (isAdsSection) {
            pageBreadcrumbs.innerHTML = 'Dashboard / <b>Buat Iklan WA Business LBA</b>';
            pageTitle.textContent = 'Buat Iklan WA Business LBA';
            pageTabLabel.textContent = 'Buat Iklan WA Business LBA';
            return;
        }

        pageBreadcrumbs.innerHTML = 'Template / <b>WA Interaktif</b>';
        pageTitle.textContent = 'WA Interaktif';
        pageTabLabel.textContent = 'WA Interaktif';
        closeAdsModal();
    }

    function syncSetupPreview() {
        userKeywordPreview.textContent = getKeywordText();
    }

    function setWabaAccount(name = '', number = '') {
        selectedWabaAccount = name.trim();
        wabaAccountName.textContent = name || 'Pilih Akun WABA';
        wabaAccountNumber.textContent = number || '';
        wabaAccountTrigger.classList.toggle('placeholder', !name);
        wabaAccountTrigger.setAttribute('aria-label', name ? `${name} ${number}` : 'Pilih Akun WABA');
    }

    function toggleWabaOptions(forceOpen) {
        const shouldOpen = typeof forceOpen === 'boolean'
            ? forceOpen
            : !wabaAccountWrap.classList.contains('open');

        wabaAccountWrap.classList.toggle('open', shouldOpen);
        wabaAccountTrigger.setAttribute('aria-expanded', shouldOpen ? 'true' : 'false');
    }

    function openSetupModal() {
        setupBackdrop.classList.add('open');
        setupModal.classList.add('open');
        setupModal.setAttribute('aria-hidden', 'false');
        toggleWabaOptions(false);
        flowNameInput.focus();
    }

    function closeSetupModal() {
        if (!isSetupComplete) {
            flowNameInput.value = '';
            setWabaAccount('', '');
            triggerKeywordInput.value = '';
            triggerKeyword = '';
            syncSetupPreview();
            toggleWabaOptions(false);
            flowNameInput.focus();
            return;
        }

        setupBackdrop.classList.remove('open');
        setupModal.classList.remove('open');
        setupModal.setAttribute('aria-hidden', 'true');
    }

    function applySetupValues() {
        if (!flowNameInput.value.trim() || !selectedWabaAccount.trim() || !triggerKeywordInput.value.trim()) {
            alert('Nama WA Interaktif, Akun WABA, dan Keyword wajib diisi.');
            return;
        }

        triggerKeyword = triggerKeywordInput.value.trim();
        isSetupComplete = true;
        app.dataset.setupComplete = 'true';
        syncSetupPreview();
        toggleWabaOptions(false);
        closeSetupModal();
    }

    function escapeHtml(value) {
        return String(value)
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#039;');
    }

    function defaultMessage(type) {
        const messages = {
            'Text Messages': 'Kirim pesan teks otomatis ke pelanggan.',
            Button: 'Tampilkan pilihan tombol cepat untuk pelanggan.',
            List: 'Tampilkan daftar opsi yang bisa dipilih pelanggan.',
            'AI Response': 'Gunakan AI untuk membalas sesuai konteks percakapan.',
            'Agent Response': 'Teruskan percakapan ke agent.',
            'Reuse Bot Response': 'Gunakan ulang response bot yang sudah tersedia.',
        };

        return messages[type] || 'Flow baru siap dikonfigurasi.';
    }

    function createNode(type) {
        const isTextMessage = type === 'Text Messages';
        const isButtonMessage = type === 'Button';
        const isListMessage = type === 'List';
        const options = isButtonMessage
            ? ['Lanjut', 'Nanti Saja']
            : (isListMessage ? ['Pilihan 1', 'Pilihan 2', 'Pilihan 3'] : []);

        return {
            id: `bot-${Date.now().toString(36)}-${flowNodes.length + 1}`,
            type: type.toLowerCase().replaceAll(' ', '_'),
            title: type,
            message: defaultMessage(type),
            headerType: (isTextMessage || isButtonMessage || isListMessage) ? 'text' : '',
            headerText: (isTextMessage || isButtonMessage || isListMessage) ? 'Promo Spesial' : '',
            headerImage: '',
            body: (isTextMessage || isButtonMessage || isListMessage) ? 'Tulis body pesan untuk pelanggan Anda di sini.' : '',
            fallback: (isTextMessage || isButtonMessage || isListMessage) ? 'Maaf, pesan belum bisa diproses. Silakan coba beberapa saat lagi.' : '',
            options,
            branchFlows: options.map((label) => createBranchFlow(label)),
            childNodes: [],
        };
    }

    function findFlowEntityById(id, collection = flowNodes) {
        for (const item of collection) {
            if (item.id === id) return item;

            const inChildren = findFlowEntityById(id, item.childNodes || []);
            if (inChildren) return inChildren;

            const inBranches = findFlowEntityById(id, item.branchFlows || []);
            if (inBranches) return inBranches;
        }

        return null;
    }

    function getNodeMessage(node) {
        if (isMessageDrawerNode(node)) {
            return node.body || 'Tulis isi body pesan.';
        }

        return node.message || defaultMessage(node.title);
    }

    function getHeaderPreview(node) {
        if (!isMessageDrawerNode(node)) {
            return '';
        }

        if (node.headerType === 'image' && node.headerImage) {
            return `
                <div class="node-header-preview image active">
                    <img src="${escapeHtml(node.headerImage)}" alt="Header image preview">
                </div>
            `;
        }

        if (node.headerType === 'text' && node.headerText) {
            return `<div class="node-header-preview active">${escapeHtml(node.headerText)}</div>`;
        }

        return '';
    }

    function getFallbackPreview(node) {
        if (!isMessageDrawerNode(node) || !node.fallback) {
            return '';
        }

        return `
            <div class="node-fallback">
                <strong>Fallback</strong>
                <span>${escapeHtml(node.fallback)}</span>
            </div>
        `;
    }

    function getButtonsPreview(node) {
        if (!['button', 'list'].includes(node.type) || !node.options?.length) {
            return '';
        }

        return `
            <div class="node-buttons">
                ${node.options.map((label) => `<span class="node-button-pill">${escapeHtml(label)}</span>`).join('')}
            </div>
        `;
    }

    function getAddNodeMarkup({ isBranch = false, targetId = '', targetKind = 'root' } = {}) {
        return `
            <div class="add-node ${isBranch ? 'branch-add' : ''}" data-add-kind="${targetKind}" data-add-target="${escapeHtml(targetId)}">
                <button class="add-response" type="button" data-action="open-menu"><span class="plus">+</span> Tambah Flow Baru</button>
                <div class="menu">
                    ${menuOptionsMarkup}
                </div>
            </div>
        `;
    }

    function getButtonBranchesPreview(node, branchFlowNumber) {
        if (node.type !== 'button' || !node.branchFlows?.length) {
            return '';
        }

        return `
            <div class="button-branches ${node.branchFlows.length > 1 ? 'two' : ''}">
                ${node.branchFlows.map((branch) => `
                    <div class="button-branch">
                        <span class="button-branch-label">${escapeHtml(branch.label || 'Button')}</span>
                        <div class="button-branch-node" data-edit-id="${branch.id}">
                            <span class="node-pill">Flow ${branchFlowNumber}</span>
                            <div class="button-branch-title">${escapeHtml(branch.title || 'Text Message')}</div>
                            <div class="button-branch-copy">
                                ${escapeHtml(branch.body || `Text lanjutan untuk tombol "${branch.label || 'Button'}"`)}
                            </div>
                        </div>
                        ${renderChildTree(branch.childNodes || [], branchFlowNumber + 1)}
                        ${getAddNodeMarkup({ isBranch: true, targetId: branch.id, targetKind: 'branch' })}
                    </div>
                `).join('')}
            </div>
        `;
    }

    function renderNodeCard(node, flowNumber) {
        return `
            <div class="bot-node" data-edit-id="${node.id}">
                <header>
                    <div class="bot-node-title">
                        <span class="node-pill">Flow ${flowNumber}</span>
                        <h2>${escapeHtml(node.title)}</h2>
                    </div>
                    <button class="remove-node" type="button" title="Hapus flow" data-remove-node="${node.id}">x</button>
                </header>
                <div class="node-content">
                    ${getHeaderPreview(node)}
                    <div class="node-message">${escapeHtml(getNodeMessage(node))}</div>
                    ${getButtonsPreview(node)}
                    ${getFallbackPreview(node)}
                </div>
            </div>
        `;
    }

    function renderChildTree(nodes, startFlowNumber) {
        if (!nodes?.length) {
            return '';
        }

        return `
            <div class="branch-children">
                ${nodes.map((node, index) => `
                    <div class="line"></div>
                    ${renderNodeCard(node, startFlowNumber + index)}
                    ${getButtonBranchesPreview(node, startFlowNumber + index + 1)}
                    ${renderChildTree(node.childNodes || [], startFlowNumber + index + 1)}
                `).join('')}
            </div>
        `;
    }

    function setDrawerHeaderType(type) {
        const headerType = type === 'image' ? 'image' : 'text';

        document.querySelectorAll('[data-header-type]').forEach((button) => {
            button.classList.toggle('active', button.dataset.headerType === headerType);
        });

        headerTextField.classList.toggle('hidden', headerType !== 'text');
        headerImageField.classList.toggle('hidden', headerType !== 'image');
    }

    function renderOptionsEditor(options = [], type = 'button') {
        const placeholder = type === 'list' ? 'Label pilihan list' : 'Label tombol';
        optionsEditorList.innerHTML = options.map((label, index) => `
            <div class="option-item">
                <input class="drawer-input" data-option-input="${index}" maxlength="40" placeholder="${placeholder}" value="${escapeHtml(label)}">
                <button class="button-remove" type="button" data-remove-button="${index}">&times;</button>
            </div>
        `).join('');

        const limit = type === 'list' ? 5 : 2;
        addOptionButton.disabled = options.length >= limit;
        addOptionButton.style.opacity = options.length >= limit ? '.5' : '1';
        optionsEditorLabel.textContent = type === 'list' ? 'List Options' : 'Buttons';
        addOptionButton.textContent = type === 'list' ? '+ List Option' : '+ Button';
    }

    function getDrawerOptionValues(limit = 2) {
        return [...optionsEditorList.querySelectorAll('[data-option-input]')]
            .map((input) => input.value.trim())
            .filter(Boolean)
            .slice(0, limit);
    }

    function openFlowEditor(entityId) {
        const entity = findFlowEntityById(entityId);
        if (!entity) return;

        activeDrawerTargetId = entityId;
        messageDrawerTitle.textContent =
            entity.type === 'button'
                ? 'Button Message'
                : entity.type === 'list'
                    ? 'List Message'
                    : (entity.label ? `Flow ${entity.label}` : 'Text Message');
        drawerHeaderText.value = entity.headerText || '';
        drawerHeaderImage.value = entity.headerImage || '';
        drawerBodyText.value = entity.body || '';
        drawerFallbackText.value = entity.fallback || '';
        setDrawerHeaderType(entity.headerType || 'text');
        optionsEditorField.classList.toggle('hidden', !['button', 'list'].includes(entity.type));
        renderOptionsEditor(entity.options || [], entity.type);
        drawerBackdrop.classList.add('open');
        textMessageDrawer.classList.add('open');
        textMessageDrawer.setAttribute('aria-hidden', 'false');
    }

    function closeTextMessageDrawer() {
        activeDrawerTargetId = null;
        drawerBackdrop.classList.remove('open');
        textMessageDrawer.classList.remove('open');
        textMessageDrawer.setAttribute('aria-hidden', 'true');
    }

    function saveTextMessageNode() {
        const activeHeaderType = document.querySelector('[data-header-type].active')?.dataset.headerType || 'text';
        if (!activeDrawerTargetId) return;

        const entity = findFlowEntityById(activeDrawerTargetId);
        if (!entity) return;

        entity.headerType = activeHeaderType;
        entity.headerText = drawerHeaderText.value.trim();
        entity.headerImage = drawerHeaderImage.value.trim();
        entity.body = drawerBodyText.value.trim();
        entity.fallback = drawerFallbackText.value.trim();
        if (['button', 'list'].includes(entity.type)) {
            entity.options = getDrawerOptionValues(entity.type === 'list' ? 5 : 2);
            syncButtonBranches(entity);
        }
        entity.message = entity.body || defaultMessage(entity.title);

        renderFlowNodes();
        closeTextMessageDrawer();
    }

    function renderFlowNodes() {
        const lastNode = flowNodes[flowNodes.length - 1];
        const shouldShowGlobalAdd = !(lastNode?.type === 'button' && lastNode.branchFlows?.length);

        botNodes.innerHTML = flowNodes.map((node, index) => `
            ${renderNodeCard(node, index + 1)}
            ${getButtonBranchesPreview(node, index + 3)}
            <div class="line"></div>
        `).join('') + (shouldShowGlobalAdd ? getAddNodeMarkup({ isBranch: false, targetKind: 'root' }) : '');
    }

    function addFlowNode(type, target = { kind: 'root', id: '' }) {
        const node = createNode(type);
        if (['button', 'list'].includes(node.type)) {
            syncButtonBranches(node);
        }

        if (target.kind === 'branch' && target.id) {
            const branch = findFlowEntityById(target.id);
            if (branch) {
                branch.childNodes = branch.childNodes || [];
                branch.childNodes.push(node);
            } else {
                flowNodes.push(node);
            }
        } else {
            flowNodes.push(node);
        }

        renderFlowNodes();

        requestAnimationFrame(() => {
            const addNodes = [...document.querySelectorAll('.add-node')];
            const addNode = addNodes[addNodes.length - 1];
            if (!addNode) return;
            const targetTop = addNode.offsetTop * zoomLevel;
            builderCanvas.scrollTo({
                top: Math.max(0, targetTop - (builderCanvas.clientHeight * .55)),
                behavior: 'smooth',
            });
        });

        requestAnimationFrame(updateMinimap);

        if (isMessageDrawerNode(node)) {
            requestAnimationFrame(() => openFlowEditor(node.id));
        }
    }

    async function submitFlow(status) {
        const flow = {
            name: flowNameInput.value.trim() || 'Untitled Flow',
            trigger: selectedTrigger,
            wabaAccount: selectedWabaAccount,
            keyword: getKeywordText(),
            status,
            nodes: [
                { id: 'start', type: 'start', title: 'Start', message: '' },
                { id: 'user-response', type: 'user_response', title: 'User Response', message: getKeywordText() },
                ...flowNodes,
            ],
        };

        await fetch('/flows/preview', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrf,
            },
            body: JSON.stringify(flow),
        });

        alert(status === 'published' ? 'Flow siap dipublish.' : 'Flow disimpan sebagai draft.');
    }

    triggerKeywordInput.addEventListener('input', (event) => {
        triggerKeyword = event.target.value;
        syncSetupPreview();
    });

    openAdsMenuButton.addEventListener('click', () => {
        setActiveSidebarSection('ads');
        openAdsModal();
    });

    adsStep1Next.addEventListener('click', () => setAdsStep(2));
    adsStep2Prev.addEventListener('click', () => setAdsStep(1));
    adsStep2Next.addEventListener('click', () => setAdsStep(3));
    adsStep3Prev.addEventListener('click', () => setAdsStep(2));
    adsStep3Next.addEventListener('click', () => {
        adsReviewTitle.textContent = adsTitleInput.value.trim() || 'asd';
        setAdsStep(4);
    });

    document.querySelectorAll('input[name="adsTemplateType"]').forEach((radio) => {
        radio.addEventListener('change', syncAdsTemplateType);
    });

    adsTimeoutMessage.addEventListener('input', syncAdsTimeoutPreview);

    templateSubmenuLink.addEventListener('click', (event) => {
        event.preventDefault();
        setActiveSidebarSection('template');
    });

    adsSubmenuLink.addEventListener('click', (event) => {
        event.preventDefault();
        setActiveSidebarSection('ads');
        openAdsModal();
    });

    adsMenuLink.addEventListener('click', (event) => {
        event.preventDefault();
        setActiveSidebarSection('ads');
        openAdsModal();
    });

    adsModalBackdrop.addEventListener('click', closeAdsModal);
    adsModalCancel.addEventListener('click', closeAdsModal);
    adsModalContinue.addEventListener('click', () => {
        pageTitle.textContent = adsTitleInput.value.trim() || 'Buat Iklan WA Business LBA';
        closeAdsModal();
    });

    wabaAccountTrigger.addEventListener('click', () => {
        toggleWabaOptions();
    });

    wabaAccountOptions.addEventListener('click', (event) => {
        const option = event.target.closest('[data-waba-name]');
        if (!option) return;

        setWabaAccount(option.dataset.wabaName || '', option.dataset.wabaNumber || '');
        toggleWabaOptions(false);
    });

    setupSubmit.addEventListener('click', applySetupValues);
    document.querySelectorAll('[data-setup-close]').forEach((button) => {
        button.addEventListener('click', closeSetupModal);
    });

    document.querySelectorAll('[data-header-type]').forEach((button) => {
        button.addEventListener('click', () => setDrawerHeaderType(button.dataset.headerType));
    });

    document.querySelectorAll('[data-drawer-close]').forEach((button) => {
        button.addEventListener('click', closeTextMessageDrawer);
    });

    drawerBackdrop.addEventListener('click', closeTextMessageDrawer);
    document.getElementById('saveTextMessage').addEventListener('click', saveTextMessageNode);
    addOptionButton.addEventListener('click', () => {
        const entity = activeDrawerTargetId ? findFlowEntityById(activeDrawerTargetId) : null;
        const type = entity?.type || 'button';
        const limit = type === 'list' ? 5 : 2;
        const options = getDrawerOptionValues(limit);
        if (options.length >= limit) return;

        options.push('');
        renderOptionsEditor(options, type);
    });

    optionsEditorList.addEventListener('click', (event) => {
        const removeButton = event.target.closest('[data-remove-button]');
        if (!removeButton) return;

        const entity = activeDrawerTargetId ? findFlowEntityById(activeDrawerTargetId) : null;
        const type = entity?.type || 'button';
        const limit = type === 'list' ? 5 : 2;
        const options = getDrawerOptionValues(limit).filter((_, index) => index !== Number(removeButton.dataset.removeButton));
        renderOptionsEditor(options, type);
    });

    document.querySelectorAll('[data-zoom-action]').forEach((button) => {
        button.addEventListener('click', () => {
            const { zoomAction } = button.dataset;

            if (zoomAction === 'in') applyZoom(zoomLevel + 0.1);
            if (zoomAction === 'out') applyZoom(zoomLevel - 0.1);
            if (zoomAction === 'preset-toggle') toggleZoomPresets();
            if (zoomAction === 'fit') fitBuilderView();
        });
    });

    document.querySelectorAll('[data-zoom-preset]').forEach((button) => {
        button.addEventListener('click', () => {
            applyZoom(Number(button.dataset.zoomPreset));
            toggleZoomPresets(false);
        });
    });

    builderCanvas.addEventListener('pointerdown', (event) => {
        if (currentStep !== 0 || !isSetupComplete) return;
        if (event.button !== 0) return;
        if (event.target.closest('button, input, textarea, select, .menu, a, label, [data-edit-id]')) return;

        isPanning = true;
        panStartX = event.clientX;
        panStartY = event.clientY;
        panScrollLeft = builderCanvas.scrollLeft;
        panScrollTop = builderCanvas.scrollTop;
        builderCanvas.classList.add('panning');
        builderCanvas.setPointerCapture(event.pointerId);
    });

    builderCanvas.addEventListener('pointermove', (event) => {
        if (!isPanning) return;

        builderCanvas.scrollLeft = panScrollLeft - (event.clientX - panStartX);
        builderCanvas.scrollTop = panScrollTop - (event.clientY - panStartY);
        updateMinimap();
    });

    function stopPanning(event) {
        if (!isPanning) return;
        isPanning = false;
        builderCanvas.classList.remove('panning');

        if (event && builderCanvas.hasPointerCapture(event.pointerId)) {
            builderCanvas.releasePointerCapture(event.pointerId);
        }
    }

    builderCanvas.addEventListener('pointerup', stopPanning);
    builderCanvas.addEventListener('pointercancel', stopPanning);
    builderCanvas.addEventListener('pointerleave', stopPanning);

    builderCanvas.addEventListener('wheel', (event) => {
        if (currentStep !== 0 || !isSetupComplete || !event.ctrlKey) return;

        event.preventDefault();
        applyZoom(zoomLevel + (event.deltaY < 0 ? 0.08 : -0.08));
    }, { passive: false });

    builderCanvas.addEventListener('scroll', updateMinimap, { passive: true });
    window.addEventListener('resize', updateMinimap);

    builderCanvas.addEventListener('click', (event) => {
        const openMenuButton = event.target.closest('[data-action="open-menu"]');
        if (openMenuButton) {
            const addNode = openMenuButton.closest('.add-node');
            const menu = addNode?.querySelector('.menu');
            if (!menu) return;

            document.querySelectorAll('.add-node .menu.open').forEach((item) => {
                if (item !== menu) item.classList.remove('open');
            });

            menu.classList.toggle('open');
            return;
        }

        const menuOption = event.target.closest('.menu [data-node]');
        if (menuOption) {
            const addNode = menuOption.closest('.add-node');
            addFlowNode(menuOption.dataset.node, {
                kind: addNode?.dataset.addKind || 'root',
                id: addNode?.dataset.addTarget || '',
            });
            menuOption.closest('.menu')?.classList.remove('open');
        }
    });

    document.addEventListener('click', (event) => {
        if (!event.target.closest('#wabaAccountWrap')) {
            toggleWabaOptions(false);
        }

        if (!event.target.closest('.add-node')) {
            document.querySelectorAll('.add-node .menu.open').forEach((item) => item.classList.remove('open'));
        }

        if (!event.target.closest('.zoom')) {
            toggleZoomPresets(false);
        }
    });

    botNodes.addEventListener('click', (event) => {
        const removeButton = event.target.closest('[data-remove-node]');
        if (removeButton) {
            flowNodes = flowNodes.filter((node) => node.id !== removeButton.dataset.removeNode);
            renderFlowNodes();
            return;
        }

        const selectedNode = event.target.closest('[data-edit-id]');
        if (selectedNode) {
            const entity = findFlowEntityById(selectedNode.dataset.editId);
            if (isMessageDrawerNode(entity)) {
                openFlowEditor(entity.id);
            }
            return;
        }
    });

    document.querySelector('[data-action="open-setup"]').addEventListener('click', openSetupModal);
    document.querySelector('[data-action="discard"]').addEventListener('click', openSetupModal);
    document.querySelector('[data-action="draft"]').addEventListener('click', () => submitFlow('draft'));
    document.querySelector('[data-action="publish"]').addEventListener('click', () => submitFlow('published'));

    renderFlowNodes();
    setActiveSidebarSection('template');
    setAdsStep(1);
    syncAdsTemplateType();
    syncAdsTimeoutPreview();
    setWabaAccount('', '');
    syncSetupPreview();
    setStep(0);
    applyZoom(1);
    updateMinimap();
    openSetupModal();
</script>
</body>
</html>
