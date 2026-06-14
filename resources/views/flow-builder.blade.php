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
        .app[data-template-view="list"] .builder-actions { display: none; }

        .workspace {
            display: none;
        }

        .workspace.active {
            display: block;
        }

        .template-view {
            display: none;
        }

        .template-view.active {
            display: block;
        }

        .template-view.template-builder-grid {
            display: none;
        }

        .template-view.template-builder-grid.active {
            display: grid;
        }

        .interactive-list {
            display: grid;
            gap: 28px;
            padding-top: 4px;
        }

        .interactive-list-actions {
            display: grid;
            grid-template-columns: 1fr minmax(280px, 520px);
            gap: 18px;
            align-items: end;
        }

        .create-interactive-btn {
            grid-column: 2;
            justify-self: end;
            min-width: 304px;
            min-height: 58px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            border: 0;
            border-radius: 8px;
            background: #ff0038;
            color: #fff;
            font-size: 18px;
            font-weight: 900;
        }

        .create-interactive-btn .plus {
            font-size: 30px;
            line-height: 1;
        }

        .interactive-search {
            position: relative;
            grid-column: 2;
        }

        .interactive-search::before {
            content: "";
            position: absolute;
            left: 20px;
            top: 50%;
            width: 16px;
            height: 16px;
            border: 2px solid #9aa3ad;
            border-radius: 50%;
            transform: translateY(-50%);
        }

        .interactive-search::after {
            content: "";
            position: absolute;
            left: 34px;
            top: 32px;
            width: 9px;
            height: 2px;
            border-radius: 999px;
            background: #9aa3ad;
            transform: rotate(45deg);
        }

        .interactive-search input {
            width: 100%;
            min-height: 46px;
            border: 1px solid var(--line);
            border-radius: 6px;
            background: #fff;
            padding: 0 18px 0 58px;
            color: var(--text);
        }

        .interactive-table-wrap {
            overflow-x: auto;
        }

        .interactive-table {
            width: 100%;
            min-width: 1180px;
            border-collapse: collapse;
            color: var(--navy);
        }

        .interactive-table th,
        .interactive-table td {
            border-bottom: 1px solid #dde2e8;
            padding: 19px 8px;
            text-align: left;
            vertical-align: middle;
            font-size: 16px;
        }

        .interactive-table th {
            font-weight: 900;
        }

        .sort-mark {
            display: inline-grid;
            gap: 2px;
            margin-left: 6px;
            vertical-align: middle;
        }

        .sort-mark::before,
        .sort-mark::after {
            content: "";
            width: 0;
            height: 0;
            border-left: 4px solid transparent;
            border-right: 4px solid transparent;
        }

        .sort-mark::before {
            border-bottom: 5px solid #d2d6dc;
        }

        .sort-mark::after {
            border-top: 5px solid #d2d6dc;
        }

        .qr-btn {
            min-width: 96px;
            min-height: 38px;
            border: 1px solid var(--navy);
            border-radius: 6px;
            background: #fff;
            color: var(--navy);
            font-weight: 800;
        }

        .qr-btn:disabled {
            border-color: #d2d6dc;
            color: #c8ccd1;
            background: #fff;
            cursor: default;
        }

        .row-actions {
            display: flex;
            align-items: center;
            gap: 13px;
            color: #a5abb3;
        }

        .icon-action {
            width: 22px;
            height: 22px;
            display: inline-grid;
            place-items: center;
            border: 0;
            background: transparent;
            color: inherit;
            padding: 0;
        }

        .icon-action svg {
            width: 20px;
            height: 20px;
            display: block;
            stroke: currentColor;
            fill: none;
            stroke-width: 1.8;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .icon-action:hover {
            color: var(--navy);
        }

        .status-toggle {
            width: 34px;
            height: 20px;
            border: 1px solid #9aa3ad;
            border-radius: 999px;
            background: #fff;
            padding: 2px;
        }

        .status-toggle::before {
            content: "";
            display: block;
            width: 14px;
            height: 14px;
            border-radius: 50%;
            background: #9aa3ad;
        }

        .status-toggle.active {
            border-color: #ff0038;
            background: #ff0038;
        }

        .status-toggle.active::before {
            margin-left: auto;
            background: #fff;
        }

        .table-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            color: var(--navy);
        }

        .per-page-control {
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .per-page-control select {
            min-width: 74px;
            min-height: 38px;
            border: 1px solid var(--line);
            border-radius: 6px;
            background: #fff;
            padding: 0 12px;
        }

        .pagination-current {
            min-width: 34px;
            min-height: 34px;
            display: grid;
            place-items: center;
            border-radius: 6px;
            background: var(--navy);
            color: #fff;
            font-weight: 900;
        }

        .session-settings-page {
            display: grid;
            gap: 24px;
            width: 100%;
        }

        .session-steps {
            position: relative;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 26px;
            padding: 0 8px 10px;
        }

        .session-steps::before {
            content: "";
            position: absolute;
            top: 18px;
            left: 8%;
            right: 8%;
            height: 1px;
            background: rgba(17, 24, 39, .18);
        }

        .session-step {
            position: relative;
            z-index: 1;
            display: grid;
            justify-items: center;
            gap: 12px;
            color: var(--navy);
            font-size: 14px;
            font-weight: 900;
            text-align: center;
        }

        .session-step-badge {
            width: 38px;
            height: 38px;
            display: grid;
            place-items: center;
            border-radius: 50%;
            background: #fff;
            color: var(--navy);
            border: 1px solid var(--line);
            font-weight: 900;
        }

        .session-step.active .session-step-badge {
            background: var(--brand);
            border-color: var(--brand);
            color: #fff;
        }

        .session-step.done .session-step-badge {
            background: var(--navy);
            border-color: var(--navy);
            color: #fff;
        }

        .session-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
        }

        .session-card {
            display: grid;
            gap: 22px;
            border: 1px solid var(--line-soft);
            border-radius: 12px;
            background: #fff;
            padding: 26px;
            box-shadow: 0 12px 28px rgba(23, 35, 50, .04);
        }

        .session-card h2 {
            margin: 0 0 6px;
            color: var(--navy);
            font-size: 20px;
        }

        .session-card p {
            margin: 0;
            color: #7b8794;
            font-size: 13px;
            line-height: 1.5;
        }

        .session-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 22px;
        }

        .session-field {
            display: grid;
            gap: 10px;
        }

        .session-field label,
        .session-message-card > span {
            color: var(--navy);
            font-size: 13px;
            font-weight: 800;
        }

        .session-input,
        .session-select,
        .session-textarea {
            width: 100%;
            border: 1px solid var(--line);
            border-radius: 8px;
            background: #fff;
            color: var(--text);
            padding: 0 16px;
        }

        .session-input,
        .session-select {
            min-height: 58px;
        }

        .session-input-wrap {
            position: relative;
        }

        .session-input-wrap .session-input {
            padding-right: 86px;
        }

        .session-input-suffix {
            position: absolute;
            right: 16px;
            top: 50%;
            color: #8b95a1;
            font-size: 13px;
            font-weight: 800;
            transform: translateY(-50%);
        }

        .session-message-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.25fr) minmax(360px, .85fr);
            gap: 22px;
        }

        .session-message-card {
            display: grid;
            gap: 12px;
            border: 1px solid var(--line-soft);
            border-radius: 12px;
            background: #fff;
            padding: 18px;
        }

        .session-toolbar {
            display: flex;
            align-items: center;
            gap: 14px;
            min-height: 46px;
            border: 1px solid var(--line-soft);
            border-radius: 8px 8px 0 0;
            padding: 8px 12px;
        }

        .session-toolbar select {
            min-width: 120px;
            border: 1px solid var(--line);
            border-radius: 6px;
            background: #fff;
            padding: 7px 10px;
            color: #526578;
            font-size: 13px;
        }

        .session-tool {
            border: 0;
            background: transparent;
            color: #526578;
            font-weight: 900;
        }

        .session-textarea {
            min-height: 164px;
            border-top: 0;
            border-radius: 0 0 8px 8px;
            padding: 16px;
            resize: vertical;
            line-height: 1.5;
        }

        .session-preview {
            min-height: 180px;
            border-radius: 12px;
            padding: 20px;
            background-color: #eee8df;
            background-image:
                radial-gradient(circle at 16px 18px, rgba(120, 110, 100, .08) 0 5px, transparent 6px),
                radial-gradient(circle at 68px 48px, rgba(120, 110, 100, .08) 0 10px, transparent 11px),
                radial-gradient(circle at 145px 24px, rgba(120, 110, 100, .08) 0 7px, transparent 8px);
            background-size: 120px 86px;
        }

        .session-review-page {
            display: grid;
            gap: 34px;
        }

        .session-review-page .ads-steps {
            display: none;
        }

        .session-review-main {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 430px;
            gap: 28px;
            align-items: start;
        }

        .session-cost-note {
            margin-top: 14px;
            color: #526578;
            font-size: 13px;
            line-height: 1.5;
        }

        .template-builder-grid {
            position: relative;
            display: grid;
            grid-template-columns: minmax(0, 1fr);
            gap: 18px;
            align-items: start;
        }

        .template-builder-grid .screen {
            min-width: 0;
        }

        .flow-editor-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, .08);
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transition: opacity .24s ease, visibility .24s ease;
            z-index: 18;
        }

        .flow-editor-backdrop.open {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
        }

        .flow-editor-panel {
            position: fixed;
            top: 146px;
            right: 24px;
            bottom: 24px;
            width: min(820px, calc(100vw - 96px));
            display: grid;
            grid-template-columns: minmax(0, 1fr) 360px;
            gap: 14px;
            align-items: start;
            padding: 4px;
            max-height: none;
            overflow: auto;
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transform: translateX(calc(100% + 32px));
            transition: transform .28s ease, opacity .2s ease, visibility .2s ease;
            z-index: 19;
        }

        .flow-editor-panel.open {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
            transform: translateX(0);
        }

        #flowEditorMount {
            min-width: 0;
        }

        .flow-editor-empty {
            grid-column: 1;
            border: 1px solid var(--line-soft);
            border-radius: 8px;
            background: #fff;
            padding: 18px;
            color: var(--muted);
            line-height: 1.5;
        }

        .flow-editor-panel.has-selection .flow-editor-empty {
            display: none;
        }

        .flow-editor-panel:not(.has-selection) .text-drawer {
            display: none;
        }

        .flow-preview-card {
            grid-column: 2;
            border: 1px solid var(--line-soft);
            border-radius: 8px;
            background: #fff;
            overflow: hidden;
            box-shadow: 0 12px 28px rgba(23, 35, 50, .05);
        }

        .flow-preview-head {
            padding: 16px 18px;
            border-bottom: 1px solid var(--line-soft);
        }

        .flow-preview-head h3 {
            margin: 0;
            color: var(--navy);
            font-size: 18px;
        }

        .wa-phone {
            margin: 18px auto 22px;
            width: min(320px, calc(100% - 32px));
            min-height: 520px;
            border: 10px solid #e8e8e8;
            border-radius: 34px;
            background: #f7f3ee;
            overflow: hidden;
            box-shadow: 0 18px 44px rgba(15, 23, 42, .14);
        }

        .wa-phone-top {
            min-height: 78px;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 18px 16px 12px;
            background: #07896f;
            color: #fff;
        }

        .wa-back {
            font-size: 26px;
            line-height: 1;
        }

        .wa-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: #d9e2e8;
            flex: 0 0 auto;
        }

        .wa-contact {
            display: grid;
            line-height: 1.1;
        }

        .wa-contact strong {
            font-size: 18px;
        }

        .wa-contact span {
            color: rgba(255,255,255,.86);
            font-size: 13px;
        }

        .wa-menu-dot {
            margin-left: auto;
            font-size: 28px;
            line-height: 1;
        }

        .wa-chat {
            min-height: 430px;
            padding: 14px;
            background-color: #eee8df;
            background-image:
                radial-gradient(circle at 16px 18px, rgba(120, 110, 100, .08) 0 5px, transparent 6px),
                radial-gradient(circle at 68px 48px, rgba(120, 110, 100, .08) 0 10px, transparent 11px),
                radial-gradient(circle at 145px 24px, rgba(120, 110, 100, .08) 0 7px, transparent 8px);
            background-size: 120px 86px;
        }

        .wa-message {
            display: grid;
            gap: 8px;
            max-width: 88%;
            border-radius: 8px;
            background: #fff;
            padding: 10px;
            color: #1f2937;
            font-size: 12px;
            line-height: 1.45;
            box-shadow: 0 8px 16px rgba(0,0,0,.05);
        }

        .wa-message-header {
            border-radius: 6px;
            background: #eef3f8;
            padding: 9px;
            color: var(--navy);
            font-weight: 800;
        }

        .wa-message-header img {
            width: 100%;
            height: 120px;
            object-fit: cover;
            border-radius: 6px;
            display: block;
        }

        .wa-message-time {
            justify-self: end;
            color: #8b9bab;
            font-size: 10px;
        }

        .wa-options {
            display: grid;
            gap: 6px;
            margin-top: 8px;
        }

        .wa-option {
            min-height: 28px;
            display: grid;
            place-items: center;
            border-top: 1px solid #edf0f2;
            color: #0785d1;
            font-weight: 700;
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

        .flow-workspace {
            display: grid;
            grid-template-columns: 248px minmax(0, 1fr);
            gap: 0;
            border: 1px solid #e4e9f1;
            border-radius: 18px;
            background: #f5f8fd;
            overflow: hidden;
            min-height: calc(100vh - 182px);
        }

        .flow-toolbox {
            display: grid;
            grid-template-rows: auto auto 1fr;
            background: #fff;
            border-right: 1px solid #e6ebf2;
        }

        .flow-toolbox-head {
            min-height: 70px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 0 18px;
            border-bottom: 1px solid #edf1f6;
        }

        .flow-toolbox-title {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            color: #1b2430;
            font-size: 18px;
            font-weight: 800;
        }

        .flow-toolbox-badge {
            color: var(--brand);
            font-weight: 900;
            letter-spacing: .08em;
        }

        .flow-toolbox-add {
            border: 0;
            background: transparent;
            color: var(--brand);
            font-size: 34px;
            line-height: 1;
        }

        .flow-toolbox-section {
            padding: 14px 18px 10px;
            border-bottom: 1px solid #edf1f6;
            color: #1b2430;
            font-size: 18px;
            font-weight: 800;
        }

        .flow-tool-list {
            display: grid;
            align-content: start;
            gap: 4px;
            padding: 12px 10px 18px;
            overflow: auto;
        }

        .flow-tool {
            border: 0;
            background: transparent;
            text-align: left;
            min-height: 34px;
            display: grid;
            grid-template-columns: 18px 1fr;
            align-items: center;
            gap: 10px;
            border-radius: 10px;
            padding: 0 10px;
            color: #213041;
            font-size: 13px;
            font-weight: 600;
        }

        .flow-tool:hover {
            background: #f7f9fc;
        }

        .flow-tool-icon {
            width: 14px;
            height: 14px;
            display: inline-grid;
            place-items: center;
            border-radius: 4px;
            background: #edf3ff;
            color: #3d6fd6;
            font-size: 10px;
            font-weight: 900;
        }

        .flow-canvas-area {
            position: relative;
            min-width: 0;
            background: #fbfdff;
        }

        .builder {
            position: relative;
            height: 100%;
            min-height: calc(100vh - 182px);
            overflow: auto;
            padding: 0;
            background:
                radial-gradient(circle, rgba(115, 151, 214, .34) 1px, transparent 1.5px) 0 0 / 24px 24px,
                #fbfdff;
            cursor: grab;
            user-select: none;
        }

        .builder.panning {
            cursor: grabbing;
        }

        .builder-stage {
            position: relative;
            min-width: 2200px;
            min-height: 1400px;
            padding: 40px 80px 260px;
            transform: scale(1);
            transform-origin: top left;
        }

        .flow-shell {
            position: relative;
            width: fit-content;
            margin: 0;
        }

        .flow-links {
            position: absolute;
            inset: 0;
            overflow: visible;
            pointer-events: none;
            z-index: 1;
        }

        .flow {
            position: relative;
            width: 360px;
            display: grid;
            justify-items: start;
        }

        .flow {
            --flow-connector-x: 114px;
        }

        .start-node {
            position: relative;
            width: 132px;
            min-height: 32px;
            display: flex;
            align-items: center;
            gap: 10px;
            border: 1.5px solid rgba(219, 61, 61, .72);
            border-radius: 8px;
            background: #fff;
            padding: 0 12px;
            color: #1b2430;
            font-size: 14px;
            font-weight: 700;
            box-shadow: 0 6px 18px rgba(17, 24, 39, .06);
            margin-left: calc(var(--flow-connector-x) - 66px);
            z-index: 2;
        }

        .start-node::before {
            content: "";
            width: 12px;
            height: 12px;
            border: 1.5px solid #49b675;
            border-radius: 50%;
            background: #fff;
            flex: 0 0 auto;
        }

        .end-node-card {
            position: relative;
            width: 132px;
            min-height: 32px;
            display: flex;
            align-items: center;
            gap: 10px;
            border: 1.5px solid rgba(219, 61, 61, .72);
            border-radius: 8px;
            background: #fff;
            padding: 0 12px;
            color: #1b2430;
            font-size: 14px;
            font-weight: 700;
            box-shadow: 0 6px 18px rgba(17, 24, 39, .06);
        }

        .end-node-card::before {
            content: "";
            width: 12px;
            height: 12px;
            border: 1.5px solid #db3d3d;
            border-radius: 50%;
            background: #fff;
            flex: 0 0 auto;
        }

        .end-node-card .link-target {
            left: -14px;
        }

        .end-node-card .remove-node {
            margin-left: auto;
        }

        .bot-nodes {
            width: 100%;
            position: relative;
            min-height: 900px;
        }

        .canvas-node {
            position: absolute;
            width: 228px;
            cursor: grab;
            z-index: 2;
        }

        .canvas-node.dragging {
            cursor: grabbing;
            z-index: 8;
        }

        .canvas-node.active .bot-node {
            box-shadow: 0 0 0 3px rgba(61, 111, 214, .18), 0 10px 26px rgba(17, 24, 39, .12);
        }

        .link-target {
            position: absolute;
            top: 50%;
            left: -12px;
            width: 18px;
            height: 18px;
            border: 2px solid #3d6fd6;
            border-radius: 999px;
            background: #fff;
            box-shadow: 0 4px 10px rgba(17, 24, 39, .08);
            transform: translateY(-50%);
            z-index: 3;
        }

        .node-output-handles {
            position: absolute;
            top: 50%;
            right: -14px;
            display: flex;
            flex-direction: column;
            gap: 8px;
            transform: translateY(-50%);
            z-index: 3;
        }

        .link-handle {
            width: 22px;
            height: 22px;
            display: grid;
            place-items: center;
            border: 1px solid rgba(61, 111, 214, .34);
            border-radius: 999px;
            background: #fff;
            color: #3d6fd6;
            font-size: 0;
            font-weight: 900;
            box-shadow: 0 4px 12px rgba(17, 24, 39, .1);
            cursor: crosshair;
        }

        .start-node > .link-handle,
        .start-link-handle {
            position: absolute;
            top: 50%;
            right: -14px;
            transform: translateY(-50%);
            z-index: 3;
        }

        .link-handle::before {
            content: "";
            width: 9px;
            height: 9px;
            border-top: 2px solid currentColor;
            border-right: 2px solid currentColor;
            transform: rotate(45deg);
            margin-left: -2px;
        }

        .link-handle:hover {
            background: #f2f7ff;
        }

        .bot-node {
            position: relative;
            width: 228px;
            border: 1.5px solid rgba(239, 80, 80, .76);
            border-radius: 10px;
            padding: 10px 12px 12px;
            background: #fff;
            box-shadow: 0 8px 22px rgba(17, 24, 39, .06);
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
            margin-bottom: 8px;
        }

        .bot-node-title {
            display: grid;
            gap: 2px;
        }

        .bot-node h2 {
            margin: 0;
            color: #1b2430;
            font-size: 14px;
            line-height: 1.25;
        }

        .node-pill {
            display: inline-flex;
            align-items: center;
            min-height: 18px;
            color: #6b7280;
            padding: 0;
            font-size: 10px;
            font-weight: 700;
            background: transparent;
        }

        .node-message {
            min-height: 24px;
            display: flex;
            align-items: center;
            border-radius: 0;
            background: transparent;
            color: #364152;
            padding: 0;
            font-size: 12px;
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
            display: grid;
            gap: 8px;
            margin-top: 4px;
        }

        .node-option-item {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .node-actions {
            display: flex;
            justify-content: flex-start;
            margin-top: 10px;
        }

        .node-view-button {
            min-height: 28px;
            border: 1px solid rgba(61, 111, 214, .18);
            border-radius: 999px;
            background: #f4f8ff;
            color: #23408b;
            padding: 0 12px;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: .01em;
        }

        .node-view-button:hover {
            background: #eaf1ff;
        }

        .node-button-pill {
            min-height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            background: linear-gradient(180deg, #ef4444 0%, #c61f1f 100%);
            color: #fff;
            padding: 0 14px;
            font-size: 11px;
            font-weight: 700;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, .24), 0 6px 12px rgba(198, 31, 31, .15);
            flex: 1;
            justify-content: center;
            text-align: center;
        }

        .node-option-item .link-handle {
            position: static;
            flex: 0 0 auto;
        }

        .button-branches {
            display: grid;
            gap: 14px;
            width: 100%;
            margin-top: 14px;
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
            min-height: 24px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            justify-self: center;
            border-radius: 999px;
            background: #fff4f4;
            color: var(--brand);
            padding: 0 10px;
            font-size: 11px;
            font-weight: 800;
        }

        .button-branch-node {
            min-height: 82px;
            display: grid;
            align-content: start;
            gap: 6px;
            border-radius: 10px;
            background: white;
            color: #526578;
            padding: 10px 12px;
            line-height: 1.45;
            box-shadow: 0 8px 22px rgba(17, 24, 39, .06);
            border: 1.5px solid rgba(239, 80, 80, .76);
        }

        .button-branch-node:hover,
        .bot-node[data-flow-node]:hover {
            border-color: rgba(227, 27, 35, .3);
        }

        .button-branch-node .node-pill {
            justify-self: start;
        }

        .button-branch-title {
            color: #1b2430;
            font-size: 13px;
            font-weight: 800;
        }

        .button-branch-copy {
            min-height: 24px;
            display: flex;
            align-items: center;
            border-radius: 0;
            background: transparent;
            padding: 0;
            box-shadow: none;
            font-size: 12px;
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
            width: 22px;
            height: 22px;
            border: 2px solid #111;
            border-radius: 999px;
            background: white;
            color: #111;
            font-size: 14px;
            font-weight: 900;
            flex: 0 0 auto;
            padding: 0;
            line-height: 1;
        }

        .add-node {
            position: relative;
            width: 228px;
        }

        .add-node.branch-add {
            margin-top: 10px;
        }

        .add-response {
            width: 228px;
            min-height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            border: 1px dashed rgba(239, 80, 80, .55);
            border-radius: 10px;
            background: rgba(255, 255, 255, .84);
            color: #516172;
            font-size: 13px;
            font-weight: 800;
            box-shadow: 0 8px 18px rgba(23, 35, 50, .04);
        }

        .add-response:hover {
            border-color: rgba(227, 27, 35, .45);
            background: #fff;
            color: var(--brand);
        }

        .add-node.branch-add .add-response {
            min-height: 38px;
            font-size: 12px;
            border-style: dashed;
        }

        .add-node.branch-add .plus {
            font-size: 26px;
        }

        .plus {
            font-size: 18px;
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

        .flow-editor-panel .text-drawer {
            position: static;
            width: 100%;
            height: auto;
            min-height: 0;
            display: grid;
            border: 1px solid var(--line-soft);
            border-radius: 8px;
            box-shadow: 0 12px 28px rgba(23, 35, 50, .05);
            transform: none;
            transition: none;
            z-index: auto;
            overflow: hidden;
        }

        .flow-editor-panel .drawer-body {
            max-height: none;
        }

        .flow-editor-panel .drawer-close,
        .flow-editor-panel [data-drawer-close] {
            display: none;
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

        .attachment-dropzone {
            min-height: 132px;
            display: grid;
            place-items: center;
            gap: 8px;
            border: 2px dashed #cbd5e1;
            border-radius: 14px;
            background: #f8fafc;
            color: #64748b;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: border-color .2s ease, background .2s ease, color .2s ease;
        }

        .attachment-dropzone:hover,
        .attachment-dropzone.dragging {
            border-color: var(--brand);
            background: #fff4f4;
            color: var(--brand);
        }

        .attachment-dropzone.disabled {
            opacity: .55;
            cursor: not-allowed;
        }

        .attachment-dropzone input {
            display: none;
        }

        .attachment-dropzone strong {
            color: var(--navy);
            font-size: 14px;
        }

        .attachment-dropzone span {
            font-size: 12px;
            line-height: 1.45;
        }

        .attachment-selected {
            display: none;
            grid-template-columns: minmax(0, 1fr) auto;
            align-items: center;
            gap: 12px;
            border: 1px solid var(--line);
            border-radius: 12px;
            background: #fff;
            padding: 12px 14px;
        }

        .attachment-selected.active {
            display: grid;
        }

        .attachment-file-info {
            min-width: 0;
            display: grid;
            gap: 3px;
        }

        .attachment-file-name {
            overflow: hidden;
            color: var(--navy);
            font-size: 13px;
            font-weight: 800;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .attachment-file-meta {
            color: #64748b;
            font-size: 11px;
        }

        .attachment-remove {
            border: 0;
            background: transparent;
            color: var(--brand);
            font-size: 12px;
            font-weight: 800;
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
            top: 10px;
            right: 16px;
            display: inline-flex;
            align-items: center;
            width: auto;
            border: 1px solid #d8e0ea;
            border-radius: 8px;
            background: white;
            box-shadow: 0 8px 20px rgba(17, 24, 39, .06);
            overflow: visible;
            z-index: 6;
        }

        .zoom button {
            min-width: 34px;
            min-height: 28px;
            border: 0;
            border-right: 1px solid #e4ebf3;
            background: white;
            color: #324256;
            font-size: 12px;
            font-weight: 800;
            line-height: 1;
            padding: 0 8px;
        }

        .zoom button.active {
            background: #fff3f3;
            color: var(--brand);
        }

        .zoom button:hover {
            background: #f7f9fb;
            color: var(--brand);
        }

        .zoom button[data-zoom-action="fit"] {
            font-size: 11px;
            letter-spacing: .03em;
        }

        .zoom button:last-child {
            border-right: 0;
        }

        .zoom-presets {
            position: absolute;
            right: 0;
            top: calc(100% + 8px);
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

            .zoom {
                left: 18px;
            }

            .interactive-list-actions {
                grid-template-columns: 1fr;
            }

            .create-interactive-btn,
            .interactive-search {
                grid-column: auto;
                justify-self: stretch;
                width: 100%;
            }

            .session-grid,
            .session-message-grid,
            .session-review-main {
                grid-template-columns: 1fr;
            }

            .session-steps {
                gap: 12px;
            }

            .template-builder-grid {
                grid-template-columns: 1fr;
            }

            .flow-workspace {
                grid-template-columns: 1fr;
            }

            .flow-toolbox {
                border-right: 0;
                border-bottom: 1px solid #e6ebf2;
            }

            .flow-editor-panel {
                top: 0;
                right: 0;
                bottom: 0;
                width: min(100vw, 560px);
                grid-template-columns: 1fr;
            }

            .flow-preview-card {
                grid-column: auto;
            }

            .flow-editor-empty {
                grid-column: auto;
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
            .menu {
                left: auto;
                right: 0;
                top: 48px;
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

    <section class="app content-shell" id="app" data-step="0" data-setup-complete="false" data-template-view="list">
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
                <section class="template-view active" id="interactiveListPage">
                    <div class="interactive-list">
                        <div class="interactive-list-actions">
                            <button class="create-interactive-btn" type="button" id="createInteractiveButton">
                                <span class="plus">+</span>
                                <span>Buat WA Interaktif</span>
                            </button>
                            <label class="interactive-search">
                                <input id="interactiveSearchInput" type="search" placeholder="Cari ID Interaktif atau Nama Interaktif">
                            </label>
                        </div>

                        <div class="interactive-table-wrap">
                            <table class="interactive-table">
                                <thead>
                                    <tr>
                                        <th>ID Interaktif<span class="sort-mark"></span></th>
                                        <th>Nama Interaktif<span class="sort-mark"></span></th>
                                        <th>Keyword<span class="sort-mark"></span></th>
                                        <th>Status<span class="sort-mark"></span></th>
                                        <th>Tanggal Publish<span class="sort-mark"></span></th>
                                        <th>Jumlah Session<span class="sort-mark"></span></th>
                                        <th>QR Code</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="interactiveTableBody">
                                    <tr>
                                        <td>44</td>
                                        <td>testmyads_cici</td>
                                        <td>Info MyAds</td>
                                        <td>Siap Digunakan</td>
                                        <td>20 Mei 2026 15:57 WIB</td>
                                        <td class="session-count-cell" data-session-count="1200">1.200</td>
                                        <td><button class="qr-btn" type="button">Lihat QR</button></td>
                                        <td>
                                            <div class="row-actions">
                                                <button class="icon-action" type="button" title="Lihat" aria-label="Lihat">
                                                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6-10-6-10-6z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                                </button>
                                                <button class="icon-action" type="button" title="Duplicate" aria-label="Duplicate">
                                                    <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="9" y="9" width="10" height="10" rx="2"></rect><path d="M15 9V7a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"></path></svg>
                                                </button>
                                                <button class="icon-action" type="button" title="Hapus" aria-label="Hapus">
                                                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 6h18"></path><path d="M8 6V4h8v2"></path><path d="M6 6l1 18h10l1-18"></path><path d="M10 11v6"></path><path d="M14 11v6"></path></svg>
                                                </button>
                                                <button class="icon-action" type="button" title="Edit Session" aria-label="Edit Session">
                                                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M8 2v4"></path><path d="M16 2v4"></path><path d="M3 10h18"></path><path d="M5 4h14a2 2 0 0 1 2 2v8.5"></path><path d="M5 4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h8.5"></path><path d="M16 19l2 2 4-4"></path></svg>
                                                </button>
                                                <button class="status-toggle active" type="button" title="Aktif"></button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>36</td>
                                        <td>a</td>
                                        <td>a</td>
                                        <td>Draft</td>
                                        <td>11 Mei 2026 11:52 WIB</td>
                                        <td class="session-count-cell" data-session-count="0">0</td>
                                        <td><button class="qr-btn" type="button" disabled>Lihat QR</button></td>
                                        <td>
                                            <div class="row-actions">
                                                <button class="icon-action" type="button" title="Lihat" aria-label="Lihat">
                                                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6-10-6-10-6z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                                </button>
                                                <button class="icon-action" type="button" title="Duplicate" aria-label="Duplicate">
                                                    <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="9" y="9" width="10" height="10" rx="2"></rect><path d="M15 9V7a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"></path></svg>
                                                </button>
                                                <button class="icon-action" type="button" title="Hapus" aria-label="Hapus">
                                                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 6h18"></path><path d="M8 6V4h8v2"></path><path d="M6 6l1 18h10l1-18"></path><path d="M10 11v6"></path><path d="M14 11v6"></path></svg>
                                                </button>
                                                <button class="icon-action" type="button" title="Edit Session" aria-label="Edit Session">
                                                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M8 2v4"></path><path d="M16 2v4"></path><path d="M3 10h18"></path><path d="M5 4h14a2 2 0 0 1 2 2v8.5"></path><path d="M5 4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h8.5"></path><path d="M16 19l2 2 4-4"></path></svg>
                                                </button>
                                                <button class="status-toggle" type="button" title="Nonaktif"></button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>17</td>
                                        <td>powerhouse</td>
                                        <td>power1</td>
                                        <td>Siap Digunakan</td>
                                        <td>22 April 2026 08:55 WIB</td>
                                        <td class="session-count-cell" data-session-count="850">850</td>
                                        <td><button class="qr-btn" type="button">Lihat QR</button></td>
                                        <td>
                                            <div class="row-actions">
                                                <button class="icon-action" type="button" title="Lihat" aria-label="Lihat">
                                                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6-10-6-10-6z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                                </button>
                                                <button class="icon-action" type="button" title="Duplicate" aria-label="Duplicate">
                                                    <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="9" y="9" width="10" height="10" rx="2"></rect><path d="M15 9V7a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"></path></svg>
                                                </button>
                                                <button class="icon-action" type="button" title="Hapus" aria-label="Hapus">
                                                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 6h18"></path><path d="M8 6V4h8v2"></path><path d="M6 6l1 18h10l1-18"></path><path d="M10 11v6"></path><path d="M14 11v6"></path></svg>
                                                </button>
                                                <button class="icon-action" type="button" title="Edit Session" aria-label="Edit Session">
                                                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M8 2v4"></path><path d="M16 2v4"></path><path d="M3 10h18"></path><path d="M5 4h14a2 2 0 0 1 2 2v8.5"></path><path d="M5 4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h8.5"></path><path d="M16 19l2 2 4-4"></path></svg>
                                                </button>
                                                <button class="status-toggle active" type="button" title="Aktif"></button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>16</td>
                                        <td>testing_roamax</td>
                                        <td>RoaMAX1</td>
                                        <td>Siap Digunakan</td>
                                        <td>21 April 2026 16:36 WIB</td>
                                        <td class="session-count-cell" data-session-count="640">640</td>
                                        <td><button class="qr-btn" type="button">Lihat QR</button></td>
                                        <td>
                                            <div class="row-actions">
                                                <button class="icon-action" type="button" title="Lihat" aria-label="Lihat">
                                                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6-10-6-10-6z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                                </button>
                                                <button class="icon-action" type="button" title="Duplicate" aria-label="Duplicate">
                                                    <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="9" y="9" width="10" height="10" rx="2"></rect><path d="M15 9V7a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"></path></svg>
                                                </button>
                                                <button class="icon-action" type="button" title="Hapus" aria-label="Hapus">
                                                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 6h18"></path><path d="M8 6V4h8v2"></path><path d="M6 6l1 18h10l1-18"></path><path d="M10 11v6"></path><path d="M14 11v6"></path></svg>
                                                </button>
                                                <button class="icon-action" type="button" title="Edit Session" aria-label="Edit Session">
                                                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M8 2v4"></path><path d="M16 2v4"></path><path d="M3 10h18"></path><path d="M5 4h14a2 2 0 0 1 2 2v8.5"></path><path d="M5 4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h8.5"></path><path d="M16 19l2 2 4-4"></path></svg>
                                                </button>
                                                <button class="status-toggle active" type="button" title="Aktif"></button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>15</td>
                                        <td>testtin3</td>
                                        <td>myads3</td>
                                        <td>Disetujui</td>
                                        <td>21 April 2026 15:33 WIB</td>
                                        <td class="session-count-cell" data-session-count="0">0</td>
                                        <td><button class="qr-btn" type="button" disabled>Lihat QR</button></td>
                                        <td>
                                            <div class="row-actions">
                                                <button class="icon-action" type="button" title="Lihat" aria-label="Lihat">
                                                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6-10-6-10-6z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                                </button>
                                                <button class="icon-action" type="button" title="Duplicate" aria-label="Duplicate">
                                                    <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="9" y="9" width="10" height="10" rx="2"></rect><path d="M15 9V7a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"></path></svg>
                                                </button>
                                                <button class="icon-action" type="button" title="Hapus" aria-label="Hapus">
                                                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 6h18"></path><path d="M8 6V4h8v2"></path><path d="M6 6l1 18h10l1-18"></path><path d="M10 11v6"></path><path d="M14 11v6"></path></svg>
                                                </button>
                                                <button class="icon-action" type="button" title="Edit Session" aria-label="Edit Session">
                                                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M8 2v4"></path><path d="M16 2v4"></path><path d="M3 10h18"></path><path d="M5 4h14a2 2 0 0 1 2 2v8.5"></path><path d="M5 4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h8.5"></path><path d="M16 19l2 2 4-4"></path></svg>
                                                </button>
                                                <button class="status-toggle" type="button" title="Nonaktif"></button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>14</td>
                                        <td>testing1</td>
                                        <td>myads1</td>
                                        <td>Disetujui</td>
                                        <td>21 April 2026 15:06 WIB</td>
                                        <td class="session-count-cell" data-session-count="0">0</td>
                                        <td><button class="qr-btn" type="button" disabled>Lihat QR</button></td>
                                        <td>
                                            <div class="row-actions">
                                                <button class="icon-action" type="button" title="Lihat" aria-label="Lihat">
                                                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6-10-6-10-6z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                                </button>
                                                <button class="icon-action" type="button" title="Duplicate" aria-label="Duplicate">
                                                    <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="9" y="9" width="10" height="10" rx="2"></rect><path d="M15 9V7a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"></path></svg>
                                                </button>
                                                <button class="icon-action" type="button" title="Hapus" aria-label="Hapus">
                                                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 6h18"></path><path d="M8 6V4h8v2"></path><path d="M6 6l1 18h10l1-18"></path><path d="M10 11v6"></path><path d="M14 11v6"></path></svg>
                                                </button>
                                                <button class="icon-action" type="button" title="Edit Session" aria-label="Edit Session">
                                                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M8 2v4"></path><path d="M16 2v4"></path><path d="M3 10h18"></path><path d="M5 4h14a2 2 0 0 1 2 2v8.5"></path><path d="M5 4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h8.5"></path><path d="M16 19l2 2 4-4"></path></svg>
                                                </button>
                                                <button class="status-toggle" type="button" title="Nonaktif"></button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>12</td>
                                        <td>template_interaktif</td>
                                        <td>bebas</td>
                                        <td>Disetujui</td>
                                        <td>21 April 2026 09:11 WIB</td>
                                        <td class="session-count-cell" data-session-count="0">0</td>
                                        <td><button class="qr-btn" type="button" disabled>Lihat QR</button></td>
                                        <td>
                                            <div class="row-actions">
                                                <button class="icon-action" type="button" title="Lihat" aria-label="Lihat">
                                                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6-10-6-10-6z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                                </button>
                                                <button class="icon-action" type="button" title="Duplicate" aria-label="Duplicate">
                                                    <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="9" y="9" width="10" height="10" rx="2"></rect><path d="M15 9V7a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"></path></svg>
                                                </button>
                                                <button class="icon-action" type="button" title="Hapus" aria-label="Hapus">
                                                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 6h18"></path><path d="M8 6V4h8v2"></path><path d="M6 6l1 18h10l1-18"></path><path d="M10 11v6"></path><path d="M14 11v6"></path></svg>
                                                </button>
                                                <button class="icon-action" type="button" title="Edit Session" aria-label="Edit Session">
                                                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M8 2v4"></path><path d="M16 2v4"></path><path d="M3 10h18"></path><path d="M5 4h14a2 2 0 0 1 2 2v8.5"></path><path d="M5 4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h8.5"></path><path d="M16 19l2 2 4-4"></path></svg>
                                                </button>
                                                <button class="status-toggle" type="button" title="Nonaktif"></button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="table-footer">
                            <label class="per-page-control">
                                <span>Tampilkan Per</span>
                                <select>
                                    <option>10</option>
                                    <option>25</option>
                                    <option>50</option>
                                </select>
                            </label>
                            <span class="pagination-current">1</span>
                        </div>
                    </div>
                </section>

                <section class="template-view" id="sessionSettingsPage">
                    <div class="session-settings-page">
                        <div class="session-steps">
                            <div class="session-step active">
                                <span class="session-step-badge">01</span>
                                <strong>Setup Session</strong>
                            </div>
                            <div class="session-step">
                                <span class="session-step-badge">02</span>
                                <strong>Review &amp; Pembayaran</strong>
                            </div>
                        </div>

                        <div class="session-actions">
                            <button class="btn" type="button" id="sessionBackButton">Kembali</button>
                            <button class="btn dark" type="button" id="sessionSaveButton">Simpan Session</button>
                        </div>

                        <section class="session-card">
                            <div>
                                <h2>Set Validity Period</h2>
                                <p>Define range time for your rule based validity</p>
                            </div>
                            <div class="session-grid">
                                <label class="session-field">
                                    <span>Start Date</span>
                                    <input class="session-input" type="date" id="sessionStartDate">
                                </label>
                                <label class="session-field">
                                    <span>End Date</span>
                                    <input class="session-input" type="date" id="sessionEndDate">
                                </label>
                            </div>
                        </section>

                        <section class="session-card">
                            <div>
                                <h2>Define Your Timeout</h2>
                                <p>Choose our duration option or custom setup by yourself</p>
                            </div>
                            <div class="session-grid">
                                <label class="session-field">
                                    <span>Choose Duration</span>
                                    <select class="session-select" id="sessionDuration">
                                        <option value="">Choose Duration</option>
                                        <option value="15">15 minutes</option>
                                        <option value="30">30 minutes</option>
                                        <option value="60">60 minutes</option>
                                    </select>
                                </label>
                                <label class="session-field">
                                    <span>Jumlah Session</span>
                                    <input class="session-input" type="number" min="1" id="sessionCountInput" placeholder="Masukkan jumlah session">
                                </label>
                            </div>
                        </section>

                        <section class="session-card">
                            <div>
                                <h2>Set Your Message Timeout</h2>
                                <p>Write the default message sent when a customer doesn't reply within the set time.</p>
                            </div>
                            <div class="session-message-grid">
                                <div class="session-message-card">
                                    <span>Message Content</span>
                                    <div>
                                        <div class="session-toolbar">
                                            <select aria-label="Paragraph style">
                                                <option>Paragraph</option>
                                            </select>
                                            <button class="session-tool" type="button">B</button>
                                            <button class="session-tool" type="button"><i>I</i></button>
                                            <button class="session-tool" type="button"><u>U</u></button>
                                            <button class="session-tool" type="button"><s>S</s></button>
                                            <button class="session-tool" type="button">1.</button>
                                            <button class="session-tool" type="button">=</button>
                                        </div>
                                        <textarea class="session-textarea" id="sessionTimeoutMessage" maxlength="1024" placeholder="Write something awesome, example &quot;Terima kasih telah berbicara dengan tim dukungan kami! Bila memiliki pertanyaan lain, silakan menghubungi kami kembali.&quot;"></textarea>
                                    </div>
                                </div>
                                <div class="session-message-card">
                                    <span>Message Preview</span>
                                    <div class="session-preview">
                                        <div class="bubble" id="sessionTimeoutPreview">Your text will appear here..<time>09:25</time></div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </section>

                <section class="template-view" id="sessionReviewPage">
                    <div class="session-review-page">
                        <div class="session-steps">
                            <div class="session-step done">
                                <span class="session-step-badge">✓</span>
                                <strong>Setup Session</strong>
                            </div>
                            <div class="session-step active">
                                <span class="session-step-badge">02</span>
                                <strong>Review &amp; Pembayaran</strong>
                            </div>
                        </div>
                        <div class="ads-steps">
                            <div class="ads-step done">
                                <span class="ads-step-badge">✓</span>
                                <strong>Pilih Template Pesan</strong>
                            </div>
                            <div class="ads-step done">
                                <span class="ads-step-badge">✓</span>
                                <strong>Atur Target Penerima</strong>
                            </div>
                            <div class="ads-step done">
                                <span class="ads-step-badge">✓</span>
                                <strong>Atur Pengiriman</strong>
                            </div>
                            <div class="ads-step active">
                                <span class="ads-step-badge">04</span>
                                <strong>Review &amp; Pembayaran</strong>
                            </div>
                        </div>

                        <div class="session-review-main">
                            <div class="ads-review-main">
                                <h2 class="ads-review-title">Review</h2>
                                <div class="ads-review-summary">
                                    <label>Judul Iklan</label>
                                    <strong>asd</strong>
                                    <a class="ads-review-link" href="#">✎ Ubah</a>
                                </div>

                                <div class="ads-review-list">
                                    <div class="ads-review-item">
                                        <span class="ads-review-icon">▤</span>
                                        <strong>Konten Iklan</strong>
                                        <a href="#">✎ Ubah</a>
                                        <button type="button">Tampilkan⌄</button>
                                    </div>
                                    <div class="ads-review-item">
                                        <span class="ads-review-icon">☷</span>
                                        <strong>Profil Penerima</strong>
                                        <a href="#">✎ Ubah</a>
                                        <button type="button">Tampilkan⌄</button>
                                    </div>
                                    <div class="ads-review-item">
                                        <span class="ads-review-icon">◷</span>
                                        <strong>Waktu Pengiriman</strong>
                                        <a href="#">✎ Ubah</a>
                                        <button type="button">Tampilkan⌄</button>
                                    </div>
                                </div>
                            </div>

                            <aside class="ads-cost-card">
                                <div class="ads-cost-body">
                                    <h3>Detil Biaya</h3>
                                    <div class="ads-cost-section">
                                        <div class="ads-cost-head">
                                            <span>PRODUK YANG DIPILIH</span>
                                            <span>⌃</span>
                                        </div>
                                        <div class="ads-cost-row"><span>Kategori Iklan</span><strong>WA Business</strong></div>
                                        <div class="ads-cost-row"><span>Tipe Kanal</span><strong>LBA</strong></div>
                                        <div class="ads-cost-row"><span>Harga Session</span><strong><span id="reviewSessionCount">0</span> x Rp 150</strong></div>
                                        <div class="session-cost-note">Biaya hanya dihitung dari jumlah session yang dimasukkan.</div>
                                    </div>

                                    <div class="ads-cost-section">
                                        <div class="ads-cost-total"><span>Grand Total <u>Tampilkan Detil</u></span><strong id="reviewGrandTotal">Rp 0</strong></div>
                                    </div>

                                    <div class="ads-cost-section">
                                        <div class="ads-cost-head">
                                            <span>SALDO &amp; PAKET ANDA</span>
                                            <span>⌃</span>
                                        </div>
                                        <div class="ads-cost-row"><span>Gunakan Paket <small style="color:#ff6b2c;">(Tersisa 0 Pesan)</small></span><strong>◯</strong></div>
                                        <div class="ads-cost-row"><span>Saldo Umum</span><strong>Rp 4.376.865</strong></div>
                                    </div>

                                    <div class="ads-cost-section">
                                        <div class="ads-cost-head">
                                            <span>PEMBAYARAN ANDA MENGGUNAKAN</span>
                                            <span>⌃</span>
                                        </div>
                                        <div class="ads-cost-total"><span>Saldo Umum</span><strong id="reviewPaymentTotal" style="color: var(--brand);">Rp 0</strong></div>
                                        <button class="ads-topup-btn" type="button" id="paySessionAdsButton">Bayar &amp; Kirim Iklan</button>
                                        <p class="session-cost-note">Apabila terdapat pesan yang tidak terkirim, maka biaya akan dikembalikan sesuai jumlah pesan yang tidak terkirim.</p>
                                    </div>
                                </div>
                            </aside>
                        </div>
                    </div>
                </section>

                <div class="template-view template-builder-grid" id="interactiveBuilderPage">
                    <div class="flow-workspace">
                        <aside class="flow-toolbox" aria-label="Flow tools">
                            <div class="flow-toolbox-head">
                                <div class="flow-toolbox-title">
                                    <span class="flow-toolbox-badge">{ }</span>
                                    <span>Variable</span>
                                </div>
                                <button class="flow-toolbox-add" type="button">+</button>
                            </div>
                            <div class="flow-toolbox-section">Tools</div>
                            <div class="flow-tool-list">
                                <button class="flow-tool" type="button" data-node="Send Text"><span class="flow-tool-icon">T</span><span>Send Text</span></button>
                                <button class="flow-tool" type="button" data-node="Send Button"><span class="flow-tool-icon">B</span><span>Send Button</span></button>
                                <button class="flow-tool" type="button" data-node="Send List"><span class="flow-tool-icon">L</span><span>Send List</span></button>
                                <button class="flow-tool" type="button" data-node="Send Media"><span class="flow-tool-icon">M</span><span>Send Media</span></button>
                                <button class="flow-tool" type="button" data-node="Chat to Agent"><span class="flow-tool-icon">C</span><span>Chat to Agent</span></button>
                                <button class="flow-tool" type="button" data-node="Send Location"><span class="flow-tool-icon">L</span><span>Send Location</span></button>
                                <button class="flow-tool" type="button" data-node="Multiple Location"><span class="flow-tool-icon">M</span><span>Multiple Location</span></button>
                                <button class="flow-tool" type="button" data-node="Request Location"><span class="flow-tool-icon">R</span><span>Request Location</span></button>
                                <button class="flow-tool" type="button" data-node="End"><span class="flow-tool-icon">E</span><span>End</span></button>
                            </div>
                        </aside>

                        <div class="flow-canvas-area">
                            <section class="screen active" data-screen="0">
                                <div class="builder" id="builderCanvas">
                                    <div class="builder-stage" id="builderStage">
                                        <div class="flow-shell" id="flowShell">
                                            <div class="flow">
                                                <div class="start-node">Start
                                                    <button class="link-handle start-link-handle" type="button" data-link-start="start" aria-label="Hubungkan Start">→</button>
                                                </div>
                                                <div class="bot-nodes" id="botNodes"></div>
                                            </div>
                                        </div>
                                        <svg class="flow-links" id="flowLinks" aria-hidden="true">
                                            <defs>
                                                <marker id="flowArrowHead" markerWidth="8" markerHeight="8" refX="6" refY="4" orient="auto">
                                                    <path d="M0,0 L8,4 L0,8 z" fill="#3d6fd6"></path>
                                                </marker>
                                            </defs>
                                        </svg>
                                    </div>

                                    <div class="zoom" aria-label="Canvas controls">
                                        <button type="button" data-zoom-action="fit">Reset</button>
                                        <button type="button" data-zoom-action="preset-toggle" id="zoomPresetToggle">100%</button>
                                        <button type="button" data-zoom-action="in">+</button>
                                        <div class="zoom-presets" id="zoomPresets">
                                            <button type="button" data-zoom-preset="1">100%</button>
                                            <button type="button" data-zoom-preset="0.75">75%</button>
                                            <button type="button" data-zoom-preset="0.5">50%</button>
                                            <button type="button" data-zoom-preset="0.25">25%</button>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>

                    <div class="flow-editor-backdrop" id="flowEditorBackdrop" aria-hidden="true"></div>
                    <aside class="flow-editor-panel" id="flowEditorPanel" aria-label="Editor dan preview flow" aria-hidden="true">
                        <div class="flow-editor-empty" id="flowEditorEmpty">
                            Pilih atau tambah flow untuk mengubah value di panel ini.
                        </div>
                        <div id="flowEditorMount"></div>
                        <section class="flow-preview-card">
                            <div class="flow-preview-head">
                                <h3>Preview</h3>
                            </div>
                            <div class="wa-phone">
                                <div class="wa-phone-top">
                                    <span class="wa-back">&lsaquo;</span>
                                    <span class="wa-avatar"></span>
                                    <span class="wa-contact">
                                        <strong>Name</strong>
                                        <span>Business Account</span>
                                    </span>
                                    <span class="wa-menu-dot">...</span>
                                </div>
                                <div class="wa-chat" id="flowMessagePreview">
                                    <div class="wa-message">Pilih flow untuk melihat preview<span class="wa-message-time">09:41</span></div>
                                </div>
                            </div>
                        </section>
                    </aside>
                </div>
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

<div class="setup-backdrop" id="setupBackdrop"></div>
<section class="setup-modal" id="setupModal" aria-modal="true" role="dialog" aria-labelledby="setupTitle" aria-hidden="true">
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
            <h2 id="messageDrawerTitle">Send Text</h2>
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
                <label for="drawerHeaderImage">Header Image</label>
                <label class="attachment-dropzone" id="headerImageDropzone" for="drawerHeaderImage">
                    <input id="drawerHeaderImage" type="file" accept="image/*">
                    <strong>Drop gambar di sini</strong>
                    <span>atau klik untuk memilih attachment dari perangkat</span>
                </label>
                <div class="attachment-selected" id="headerImageSelected">
                    <div class="attachment-file-info">
                        <span class="attachment-file-name" id="headerImageFileName"></span>
                        <span class="attachment-file-meta" id="headerImageFileMeta"></span>
                    </div>
                    <button class="attachment-remove" id="removeHeaderImage" type="button">Hapus</button>
                </div>
            </div>

            <div class="drawer-field">
                <label for="drawerBodyText">Body</label>
                <textarea class="drawer-textarea" id="drawerBodyText" maxlength="1024" placeholder="Tulis isi pesan di sini"></textarea>
            </div>

            <div class="drawer-field">
                <label for="drawerFallbackText">Fallback</label>
                <textarea class="drawer-textarea" id="drawerFallbackText" maxlength="1024" placeholder="Tulis pesan fallback jika dibutuhkan"></textarea>
            </div>

            <div class="drawer-field hidden" id="emailToField">
                <label for="emailToInput">To</label>
                <input class="drawer-input" id="emailToInput" maxlength="255" placeholder="customer@email.com">
            </div>

            <div class="drawer-field hidden" id="emailCcField">
                <label for="emailCcInput">CC</label>
                <input class="drawer-input" id="emailCcInput" maxlength="255" placeholder="ops@email.com, sales@email.com">
            </div>

            <div class="drawer-field hidden" id="emailSubjectField">
                <label for="emailSubjectInput">Subject</label>
                <input class="drawer-input" id="emailSubjectInput" maxlength="255" placeholder="Masukkan subject email">
            </div>

            <div class="drawer-field hidden" id="agentSelectorField">
                <label for="agentSelector">Pilih Agent</label>
                <select class="drawer-input" id="agentSelector"></select>
                <small id="agentSelectorHelp" style="display:block;margin-top:8px;color:#6b7280;font-size:12px;"></small>
            </div>

            <div class="drawer-field hidden" id="mediaTypeField">
                <label for="mediaTypeSelector">Tipe Media</label>
                <select class="drawer-input" id="mediaTypeSelector"></select>
            </div>

            <div class="drawer-field hidden" id="mediaSourceField">
                <label for="mediaSourceInput">Attachment Media</label>
                <label class="attachment-dropzone disabled" id="mediaSourceDropzone" for="mediaSourceInput">
                    <input id="mediaSourceInput" type="file" disabled>
                    <strong id="mediaDropzoneTitle">Pilih tipe media terlebih dahulu</strong>
                    <span id="mediaDropzoneText">Setelah itu drop file di sini atau klik untuk memilih attachment</span>
                </label>
                <div class="attachment-selected" id="mediaSourceSelected">
                    <div class="attachment-file-info">
                        <span class="attachment-file-name" id="mediaSourceFileName"></span>
                        <span class="attachment-file-meta" id="mediaSourceFileMeta"></span>
                    </div>
                    <button class="attachment-remove" id="removeMediaSource" type="button">Hapus</button>
                </div>
                <small id="mediaSourceHelp" style="display:block;margin-top:8px;color:#6b7280;font-size:12px;">Pilih tipe media terlebih dahulu</small>
            </div>

            <div class="drawer-field hidden" id="locationNameField">
                <label for="locationNameInput">Nama Lokasi</label>
                <input class="drawer-input" id="locationNameInput" maxlength="255" placeholder="Nama Lokasi">
            </div>

            <div class="drawer-field hidden" id="locationSearchField">
                <button class="btn" type="button" id="locationSearchButton">Cari Lokasi</button>
                <small id="locationSearchHelp" style="display:block;margin-top:8px;color:#6b7280;font-size:12px;">Klik untuk memilih atau mencari lokasi.</small>
            </div>

            <div class="drawer-field hidden" id="requestLocationInfoField">
                <small id="requestLocationInfoHelp" style="display:block;color:#1f2d3d;font-size:14px;">This contains a Request Location Feature.</small>
            </div>

            <div class="drawer-field hidden" id="templateSelectorField">
                <label for="templateSelector">Pilih Template</label>
                <select class="drawer-input" id="templateSelector"></select>
                <small id="templateSelectorHelp" style="display:block;margin-top:8px;color:#6b7280;font-size:12px;"></small>
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
    const interactiveListPage = document.getElementById('interactiveListPage');
    const interactiveBuilderPage = document.getElementById('interactiveBuilderPage');
    const sessionSettingsPage = document.getElementById('sessionSettingsPage');
    const sessionReviewPage = document.getElementById('sessionReviewPage');
    const createInteractiveButton = document.getElementById('createInteractiveButton');
    const interactiveSearchInput = document.getElementById('interactiveSearchInput');
    const interactiveTableBody = document.getElementById('interactiveTableBody');
    const flowToolList = document.querySelector('.flow-tool-list');
    const sessionBackButton = document.getElementById('sessionBackButton');
    const sessionSaveButton = document.getElementById('sessionSaveButton');
    const sessionCountInput = document.getElementById('sessionCountInput');
    const sessionTimeoutMessage = document.getElementById('sessionTimeoutMessage');
    const sessionTimeoutPreview = document.getElementById('sessionTimeoutPreview');
    const reviewSessionCount = document.getElementById('reviewSessionCount');
    const reviewGrandTotal = document.getElementById('reviewGrandTotal');
    const reviewPaymentTotal = document.getElementById('reviewPaymentTotal');
    const paySessionAdsButton = document.getElementById('paySessionAdsButton');
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
    const flowLinks = document.getElementById('flowLinks');
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
    const drawerBackdrop = document.getElementById('drawerBackdrop');
    const textMessageDrawer = document.getElementById('textMessageDrawer');
    const messageDrawerTitle = document.getElementById('messageDrawerTitle');
    const headerSection = textMessageDrawer.querySelector('.drawer-section');
    const headerTextField = document.getElementById('headerTextField');
    const headerImageField = document.getElementById('headerImageField');
    const drawerHeaderText = document.getElementById('drawerHeaderText');
    const drawerHeaderImage = document.getElementById('drawerHeaderImage');
    const headerImageDropzone = document.getElementById('headerImageDropzone');
    const headerImageSelected = document.getElementById('headerImageSelected');
    const headerImageFileName = document.getElementById('headerImageFileName');
    const headerImageFileMeta = document.getElementById('headerImageFileMeta');
    const removeHeaderImage = document.getElementById('removeHeaderImage');
    const drawerBodyText = document.getElementById('drawerBodyText');
    const drawerFallbackText = document.getElementById('drawerFallbackText');
    const emailToField = document.getElementById('emailToField');
    const emailCcField = document.getElementById('emailCcField');
    const emailSubjectField = document.getElementById('emailSubjectField');
    const emailToInput = document.getElementById('emailToInput');
    const emailCcInput = document.getElementById('emailCcInput');
    const emailSubjectInput = document.getElementById('emailSubjectInput');
    const agentSelectorField = document.getElementById('agentSelectorField');
    const agentSelector = document.getElementById('agentSelector');
    const agentSelectorHelp = document.getElementById('agentSelectorHelp');
    const mediaTypeField = document.getElementById('mediaTypeField');
    const mediaTypeSelector = document.getElementById('mediaTypeSelector');
    const mediaSourceField = document.getElementById('mediaSourceField');
    const mediaSourceInput = document.getElementById('mediaSourceInput');
    const mediaSourceDropzone = document.getElementById('mediaSourceDropzone');
    const mediaSourceSelected = document.getElementById('mediaSourceSelected');
    const mediaSourceFileName = document.getElementById('mediaSourceFileName');
    const mediaSourceFileMeta = document.getElementById('mediaSourceFileMeta');
    const mediaDropzoneTitle = document.getElementById('mediaDropzoneTitle');
    const mediaDropzoneText = document.getElementById('mediaDropzoneText');
    const removeMediaSource = document.getElementById('removeMediaSource');
    const mediaSourceHelp = document.getElementById('mediaSourceHelp');
    const locationNameField = document.getElementById('locationNameField');
    const locationNameInput = document.getElementById('locationNameInput');
    const locationSearchField = document.getElementById('locationSearchField');
    const locationSearchButton = document.getElementById('locationSearchButton');
    const locationSearchHelp = document.getElementById('locationSearchHelp');
    const requestLocationInfoField = document.getElementById('requestLocationInfoField');
    const templateSelectorField = document.getElementById('templateSelectorField');
    const templateSelector = document.getElementById('templateSelector');
    const templateSelectorHelp = document.getElementById('templateSelectorHelp');
    const optionsEditorField = document.getElementById('optionsEditorField');
    const optionsEditorLabel = document.getElementById('optionsEditorLabel');
    const optionsEditorList = document.getElementById('optionsEditorList');
    const addOptionButton = document.getElementById('addOptionButton');
    const flowEditorPanel = document.getElementById('flowEditorPanel');
    const flowEditorBackdrop = document.getElementById('flowEditorBackdrop');
    const flowEditorMount = document.getElementById('flowEditorMount');
    const flowMessagePreview = document.getElementById('flowMessagePreview');
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
    let activeSessionRow = null;
    let activeDragNodeId = null;
    let dragPointerId = null;
    let dragStartX = 0;
    let dragStartY = 0;
    let dragNodeOriginX = 0;
    let dragNodeOriginY = 0;
    let flowConnections = [];
    let activeLinkDrag = null;
    let headerImageAttachment = { name: '', type: '', size: 0, dataUrl: '' };
    let mediaAttachment = { name: '', type: '', size: 0, dataUrl: '' };
    const menuOptionsMarkup = `
        <button type="button" data-node="Send Text">Send Text</button>
        <button type="button" data-node="Send Button">Send Button</button>
        <button type="button" data-node="Send List">Send List</button>
        <button type="button" data-node="Send Media">Send Media</button>
        <button type="button" data-node="Chat to Agent">Chat to Agent</button>
        <button type="button" data-node="Send Location">Send Location</button>
        <button type="button" data-node="Multiple Location">Multiple Location</button>
        <button type="button" data-node="Request Location">Request Location</button>
        <button type="button" data-node="End">End</button>
    `;

    flowEditorMount.appendChild(textMessageDrawer);
    textMessageDrawer.setAttribute('aria-hidden', 'false');

    const TOOL_NODE_PRESETS = {
        'Send Text': {
            type: 'text_messages',
            headerType: 'text',
            headerText: 'Promo Spesial',
            body: 'Tulis body pesan untuk pelanggan Anda di sini.',
            fallback: 'Maaf, pesan belum bisa diproses. Silakan coba beberapa saat lagi.',
        },
        'Send Button': {
            type: 'button',
            headerType: 'text',
            headerText: 'Promo Spesial',
            body: 'Tulis body pesan untuk pelanggan Anda di sini.',
            fallback: 'Maaf, pesan belum bisa diproses. Silakan coba beberapa saat lagi.',
            options: ['Lanjut', 'Nanti Saja'],
        },
        'Send Template': {
            type: 'send_template',
            headerType: 'text',
            headerText: 'Template Resmi',
            body: 'Pilih template WhatsApp yang sudah disetujui, lalu isi parameter yang diperlukan sebelum dikirim.',
            fallback: 'Template belum siap atau belum tersinkronisasi.',
        },
        'Send Survey': {
            type: 'send_survey',
            headerType: 'text',
            headerText: 'Survey Singkat',
            body: 'Ajukan survey singkat untuk mengetahui kebutuhan atau kepuasan pelanggan.',
            fallback: 'Survey belum dapat ditampilkan saat ini.',
            options: ['Sangat Puas', 'Cukup Puas', 'Butuh Bantuan'],
        },
        'Send List': {
            type: 'list',
            headerType: 'text',
            headerText: 'Promo Spesial',
            body: 'Tulis body pesan untuk pelanggan Anda di sini.',
            fallback: 'Maaf, pesan belum bisa diproses. Silakan coba beberapa saat lagi.',
            options: ['Pilihan 1', 'Pilihan 2', 'Pilihan 3'],
        },
        'Waiting Response': {
            type: 'waiting_response',
            headerType: 'text',
            headerText: 'Menunggu Balasan',
            body: 'Tahan flow sampai pelanggan mengirimkan balasan atau sampai kondisi timeout tercapai.',
            fallback: 'Pelanggan belum memberikan balasan dalam waktu yang ditentukan.',
        },
        'Validation': {
            type: 'validation',
            headerType: 'text',
            headerText: 'Cek Input',
            body: 'Validasi input bebas dari pelanggan, misalnya email, nomor HP, atau format data tertentu setelah Waiting Response.',
            fallback: 'Input pelanggan tidak valid. Minta pelanggan mengirim ulang data.',
        },
        'Send Email': {
            type: 'send_email',
            headerType: 'text',
            headerText: 'Email Notification',
            body: 'Kirim email notifikasi berdasarkan data yang terkumpul dari percakapan.',
            fallback: 'Email belum berhasil dikirim. Silakan cek kembali konfigurasinya.',
            emailTo: '@{{customer_email}}',
            emailCc: '',
            emailSubject: 'Notifikasi dari Flow Builder',
        },
        'Branch': {
            type: 'branch',
            headerType: 'text',
            headerText: 'Percabangan',
            body: 'Pisahkan alur berdasarkan kondisi tertentu atau keputusan pelanggan.',
            fallback: '',
            options: ['Ya', 'Tidak'],
        },
        'HTTP Request': {
            type: 'http_request',
            headerType: 'text',
            headerText: 'API Request',
            body: 'Kirim request ke endpoint eksternal, simpan response yang dibutuhkan, lalu arahkan flow berdasarkan hasil request.',
            fallback: 'Request gagal dijalankan atau endpoint tidak merespons.',
            options: ['Response Received', 'Network Error'],
        },
        'Send Media': {
            type: 'send_media',
            headerType: 'image',
            headerText: '',
            body: 'Kirim gambar, video, audio, atau dokumen kepada pelanggan.',
            fallback: 'Media belum tersedia untuk dikirim saat ini.',
            mediaType: '',
            mediaSource: '',
        },
        'Send Catalog': {
            type: 'send_catalog',
            headerType: 'text',
            headerText: 'Katalog Produk',
            body: 'Tampilkan katalog produk yang paling relevan untuk pelanggan.',
            fallback: 'Katalog belum tersedia saat ini.',
        },
        'Chat to Agent': {
            type: 'chat_to_agent',
            headerType: 'text',
            headerText: 'Alihkan ke Agent',
            body: 'Teruskan percakapan aktif ke agent yang tersedia.',
            fallback: 'Belum ada agent yang tersedia saat ini.',
            agentId: 'agent_dina',
        },
        'Assign Variable': {
            type: 'assign_variable',
            headerType: 'text',
            headerText: 'Simpan Variable',
            body: 'Simpan nilai tertentu dari pelanggan ke variable flow untuk dipakai di langkah berikutnya.',
            fallback: '',
        },
        'Receive Order': {
            type: 'receive_order',
            headerType: 'text',
            headerText: 'Data Order',
            body: 'Tunggu dan validasi data pesanan pelanggan sebelum diteruskan ke proses order berikutnya.',
            fallback: 'Detail pesanan belum lengkap. Minta pelanggan melengkapi datanya.',
            options: ['Order Diterima', 'Perlu Verifikasi'],
        },
        'Receive Product Inquiry': {
            type: 'receive_product_inquiry',
            headerType: 'text',
            headerText: 'Pertanyaan Produk',
            body: 'Tangkap pertanyaan produk dari pelanggan lalu arahkan ke jawaban otomatis atau ke agent bila perlu.',
            fallback: 'Pertanyaan produk belum bisa diproses saat ini.',
            options: ['Pertanyaan Diterima', 'Perlu Klarifikasi'],
        },
        'Send Location': {
            type: 'send_location',
            headerType: 'text',
            headerText: 'Lokasi Kami',
            body: 'Kirim satu lokasi utama bisnis atau titik layanan kepada pelanggan.',
            fallback: 'Lokasi belum tersedia untuk dikirim saat ini.',
            locationName: '',
        },
        'Multiple Location': {
            type: 'multiple_location',
            headerType: 'text',
            headerText: 'Pilih Lokasi',
            body: 'Tampilkan beberapa lokasi agar pelanggan bisa memilih cabang terdekat.',
            fallback: 'Daftar lokasi belum tersedia saat ini.',
            options: ['Lokasi 1', 'Lokasi 2', 'Lokasi 3'],
        },
        'WA Flow': {
            type: 'wa_flow',
            headerType: 'text',
            headerText: 'WhatsApp Flow',
            body: 'Pilih WhatsApp Flow yang sudah tersedia dari Meta/template sync untuk mengumpulkan input pelanggan secara terstruktur.',
            fallback: 'WhatsApp Flow belum tersedia atau belum tersinkronisasi.',
        },
        'Request Location': {
            type: 'request_location',
            headerType: 'text',
            headerText: 'Minta Lokasi',
            body: 'Minta pelanggan mengirimkan lokasi terkini mereka sebelum flow dilanjutkan.',
            fallback: 'Lokasi pelanggan belum diterima.',
        },
        'Call To Agent': {
            type: 'call_to_agent',
            headerType: 'text',
            headerText: 'Hubungi Agent',
            body: 'Arahkan pelanggan untuk terhubung langsung ke agent melalui panggilan.',
            fallback: 'Agent panggilan belum tersedia saat ini.',
            agentId: 'agent_dina',
        },
        'End': {
            type: 'end',
            headerType: '',
            headerText: '',
            body: '',
            fallback: '',
        },
    };

    const OPTION_NODE_TYPES = {
        button: { limit: 2, label: 'Buttons', addLabel: '+ Button', placeholder: 'Label tombol' },
        list: { limit: 5, label: 'List Options', addLabel: '+ List Option', placeholder: 'Label pilihan list' },
        send_survey: { limit: 5, label: 'Survey Options', addLabel: '+ Survey Option', placeholder: 'Pilihan survey' },
        branch: { limit: 4, label: 'Branch Paths', addLabel: '+ Branch Path', placeholder: 'Label percabangan' },
        http_request: { limit: 4, label: 'HTTP Routes', addLabel: '+ HTTP Route', placeholder: 'Label route HTTP' },
        receive_order: { limit: 3, label: 'Order Paths', addLabel: '+ Order Path', placeholder: 'Label status order' },
        receive_product_inquiry: { limit: 3, label: 'Inquiry Paths', addLabel: '+ Inquiry Path', placeholder: 'Label pertanyaan' },
        multiple_location: { limit: 5, label: 'Location Options', addLabel: '+ Location Option', placeholder: 'Nama lokasi' },
    };

    const TEMPLATE_LIBRARY = [
        {
            id: 'promo_bulanan',
            name: 'Promo Bulanan',
            category: 'Marketing',
            content: 'Halo {{1}}, ada promo bulanan terbaru yang bisa kamu gunakan hari ini.',
        },
        {
            id: 'reminder_pembayaran',
            name: 'Reminder Pembayaran',
            category: 'Utility',
            content: 'Halo {{1}}, kami mengingatkan bahwa pembayaran untuk order {{2}} akan jatuh tempo hari ini.',
        },
        {
            id: 'otp_login',
            name: 'OTP Login',
            category: 'Authentication',
            content: 'Kode OTP kamu adalah {{1}}. Jangan bagikan kode ini kepada siapa pun.',
        },
    ];

    const AGENT_LIBRARY = [
        {
            id: 'agent_dina',
            name: 'Dina Support',
            team: 'Customer Support',
            note: 'Cocok untuk percakapan umum dan bantuan pelanggan.',
        },
        {
            id: 'agent_budi',
            name: 'Budi Sales',
            team: 'Sales Team',
            note: 'Cocok untuk follow-up prospek, penawaran, dan closing.',
        },
        {
            id: 'agent_rina',
            name: 'Rina Priority Care',
            team: 'Priority Care',
            note: 'Cocok untuk pelanggan prioritas dan eskalasi khusus.',
        },
    ];

    const MEDIA_TYPE_LIBRARY = [
        { id: '', name: 'Pilih tipe media', accept: '' },
        { id: 'image', name: 'Image', accept: 'image/*' },
        { id: 'video', name: 'Video', accept: 'video/*' },
        { id: 'document', name: 'Document', accept: '.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.csv,application/pdf' },
        { id: 'audio', name: 'Audio', accept: 'audio/*' },
    ];

    function getToolPreset(type) {
        return TOOL_NODE_PRESETS[type] || null;
    }

    function getOptionNodeConfig(type) {
        return OPTION_NODE_TYPES[type] || null;
    }

    function getTemplateById(templateId) {
        return TEMPLATE_LIBRARY.find((template) => template.id === templateId) || TEMPLATE_LIBRARY[0] || null;
    }

    function getAgentById(agentId) {
        return AGENT_LIBRARY.find((agent) => agent.id === agentId) || AGENT_LIBRARY[0] || null;
    }

    function getMediaTypeById(mediaTypeId) {
        return MEDIA_TYPE_LIBRARY.find((mediaType) => mediaType.id === mediaTypeId) || MEDIA_TYPE_LIBRARY[0];
    }

    function isMessageDrawerNode(node) {
        return Boolean(node && node.type !== 'end');
    }

    function createBranchFlow(label, sourceType = 'button') {
        const branchLabel = label || (sourceType === 'branch' ? 'Path' : 'Button');
        return {
            id: `branch-${Date.now().toString(36)}-${Math.random().toString(36).slice(2, 7)}`,
            label: branchLabel,
            title: 'Send Text',
            type: 'text_messages',
            headerType: 'text',
            headerText: '',
            headerImage: '',
            body: sourceType === 'branch'
                ? `Lanjutan flow untuk cabang "${branchLabel}"`
                : `Text lanjutan untuk tombol "${branchLabel}"`,
            fallback: '',
            childNodes: [],
        };
    }

    function syncButtonBranches(node) {
        if (!['button', 'list', 'branch'].includes(node.type)) return;

        const limit = node.type === 'button' ? 2 : (node.type === 'list' ? 5 : 4);
        const labels = (node.options || []).slice(0, limit);
        const existing = node.branchFlows || [];

        node.branchFlows = labels.map((label, index) => {
            const branch = existing[index] || createBranchFlow(label, node.type);
            branch.label = label || (node.type === 'branch' ? `Path ${index + 1}` : `Button ${index + 1}`);
            if (!branch.body) {
                branch.body = node.type === 'branch'
                    ? `Lanjutan flow untuk cabang "${branch.label}"`
                    : `Text lanjutan untuk tombol "${branch.label}"`;
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
                alignBuilderTopLeft();
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
        builderStage.style.transform = `scale(${zoomLevel})`;
        syncZoomControls();
        renderFlowConnections();
    }

    function alignBuilderTopLeft() {
        builderCanvas.scrollLeft = 0;
        builderCanvas.scrollTop = 0;
    }

    function centerBuilderView() {
        const left = (builderStage.scrollWidth - builderCanvas.clientWidth) / 2;
        const top = Math.max(0, Math.min(
            builderStage.scrollHeight - builderCanvas.clientHeight,
            180
        ));

        builderCanvas.scrollLeft = Math.max(0, left);
        builderCanvas.scrollTop = top;
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

    function resetInteractiveBuilder() {
        flowNameInput.value = '';
        setWabaAccount('', '');
        triggerKeywordInput.value = '';
        triggerKeyword = '';
        selectedTrigger = 'Inbound';
        flowNodes = [];
        isSetupComplete = false;
        app.dataset.setupComplete = 'false';
        closeTextMessageDrawer();
        renderFlowNodes();
        syncSetupPreview();
        applyZoom(1);
    }

    function showInteractiveList() {
        app.dataset.templateView = 'list';
        interactiveListPage.classList.add('active');
        sessionSettingsPage.classList.remove('active');
        sessionReviewPage.classList.remove('active');
        interactiveBuilderPage.classList.remove('active');
        closeTextMessageDrawer();
        setupBackdrop.classList.remove('open');
        setupModal.classList.remove('open');
        setupModal.setAttribute('aria-hidden', 'true');
        pageBreadcrumbs.innerHTML = 'Template / <b>WA Interaktif</b>';
        pageTitle.textContent = 'WA Interaktif';
        pageTabLabel.textContent = 'WA Interaktif';
    }

    function showInteractiveBuilder() {
        app.dataset.templateView = 'builder';
        interactiveListPage.classList.remove('active');
        sessionSettingsPage.classList.remove('active');
        sessionReviewPage.classList.remove('active');
        interactiveBuilderPage.classList.add('active');
        closeTextMessageDrawer();
        requestAnimationFrame(() => {
            alignBuilderTopLeft();
            applyZoom(zoomLevel);
        });
    }

    function showSessionSettings() {
        app.dataset.templateView = 'session';
        interactiveListPage.classList.remove('active');
        interactiveBuilderPage.classList.remove('active');
        sessionReviewPage.classList.remove('active');
        sessionSettingsPage.classList.add('active');
        closeTextMessageDrawer();
    }

    function formatRupiah(value) {
        return `Rp ${Math.max(0, Number(value) || 0).toLocaleString('id-ID')}`;
    }

    function formatSessionCount(value) {
        return Math.max(0, Number(value) || 0).toLocaleString('id-ID');
    }

    function syncInteractiveSessionCount(row, count) {
        const sessionCell = row?.querySelector('.session-count-cell');
        if (!sessionCell) return;

        const normalizedCount = Math.max(0, Number(count) || 0);
        sessionCell.dataset.sessionCount = String(normalizedCount);
        sessionCell.textContent = formatSessionCount(normalizedCount);
    }

    function setFlowEditorPanelOpen(isOpen) {
        flowEditorPanel.classList.toggle('open', isOpen);
        flowEditorBackdrop.classList.toggle('open', isOpen);
        flowEditorPanel.setAttribute('aria-hidden', isOpen ? 'false' : 'true');
        flowEditorBackdrop.setAttribute('aria-hidden', isOpen ? 'false' : 'true');
    }

    function showSessionReview() {
        const sessionCount = Math.max(0, Number(sessionCountInput.value) || 0);
        const grandTotal = sessionCount * 150;

        reviewSessionCount.textContent = sessionCount.toLocaleString('id-ID');
        reviewGrandTotal.textContent = formatRupiah(grandTotal);
        reviewPaymentTotal.textContent = formatRupiah(grandTotal);

        app.dataset.templateView = 'review';
        interactiveListPage.classList.remove('active');
        sessionSettingsPage.classList.remove('active');
        interactiveBuilderPage.classList.remove('active');
        sessionReviewPage.classList.add('active');
        pageBreadcrumbs.innerHTML = 'Dashboard / <b>Buat Iklan WA Business LBA</b>';
        pageTitle.textContent = 'Buat Iklan WA Business LBA';
        pageTabLabel.textContent = 'Review & Pembayaran';
    }

    function syncSessionTimeoutPreview() {
        if (!sessionTimeoutMessage || !sessionTimeoutPreview) return;

        const value = sessionTimeoutMessage.value.trim() || 'Your text will appear here..';
        sessionTimeoutPreview.innerHTML = `${escapeHtml(value)}<time>09:25</time>`;
    }

    function startCreateInteractive() {
        resetInteractiveBuilder();
        showInteractiveBuilder();
        openSetupModal();
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
        showInteractiveList();
    }

    function syncSetupPreview() {}

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
            showInteractiveList();
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
            'Send Text': 'Kirim pesan teks otomatis ke pelanggan.',
            'Send Button': 'Tampilkan pilihan tombol cepat untuk pelanggan.',
            'Send Template': 'Kirim template WhatsApp yang sudah disetujui.',
            'Send Survey': 'Kirim survey singkat untuk mengumpulkan jawaban pelanggan.',
            'Send List': 'Tampilkan daftar opsi yang bisa dipilih pelanggan.',
            'Waiting Response': 'Tunggu sampai pelanggan mengirimkan balasan.',
            'Validation': 'Validasi data atau jawaban pelanggan sebelum lanjut.',
            'Send Email': 'Kirim email notifikasi dari data percakapan.',
            'Branch': 'Pisahkan flow berdasarkan kondisi atau keputusan tertentu.',
            'HTTP Request': 'Hubungkan flow dengan API atau sistem eksternal.',
            'Send Media': 'Kirim media seperti gambar, video, atau dokumen.',
            'Send Catalog': 'Tampilkan katalog produk kepada pelanggan.',
            'Chat to Agent': 'Alihkan percakapan ke agent yang tersedia.',
            'Assign Variable': 'Simpan nilai ke variable untuk langkah berikutnya.',
            'Receive Order': 'Terima dan olah data pesanan pelanggan.',
            'Receive Product Inquiry': 'Terima pertanyaan produk dari pelanggan.',
            'Send Location': 'Kirim lokasi bisnis kepada pelanggan.',
            'Multiple Location': 'Tampilkan beberapa pilihan lokasi.',
            'WA Flow': 'Jalankan WhatsApp Flow terstruktur.',
            'Request Location': 'Minta pelanggan mengirim lokasinya.',
            'Call To Agent': 'Hubungkan pelanggan ke agent melalui panggilan.',
            'End': 'Akhiri alur percakapan pada node ini.',
            'AI Response': 'Gunakan AI untuk membalas sesuai konteks percakapan.',
            'Agent Response': 'Teruskan percakapan ke agent.',
            'Reuse Bot Response': 'Gunakan ulang response bot yang sudah tersedia.',
        };

        return messages[type] || 'Flow baru siap dikonfigurasi.';
    }

    function createNode(type) {
        const preset = getToolPreset(type);
        const options = [...(preset?.options || [])];
        const position = getNextNodePosition();
        const nodeType = preset?.type || type.toLowerCase().replaceAll(' ', '_');

        return {
            id: `bot-${Date.now().toString(36)}-${flowNodes.length + 1}`,
            type: nodeType,
            title: type,
            message: defaultMessage(type),
            headerType: preset?.headerType || '',
            headerText: preset?.headerText || '',
            headerImage: '',
            body: preset?.body || '',
            fallback: preset?.fallback || '',
            emailTo: preset?.emailTo || '',
            emailCc: preset?.emailCc || '',
            emailSubject: preset?.emailSubject || '',
            agentId: preset?.agentId || '',
            mediaType: preset?.mediaType || '',
            mediaSource: preset?.mediaSource || '',
            locationName: preset?.locationName || '',
            templateId: nodeType === 'send_template' ? (TEMPLATE_LIBRARY[0]?.id || '') : '',
            options,
            branchFlows: options.map((label) => createBranchFlow(label, nodeType)),
            childNodes: [],
            x: position.x,
            y: position.y,
        };
    }

    function getNextNodePosition() {
        const lastNode = flowNodes[flowNodes.length - 1];
        if (lastNode) {
            const nextX = (Number(lastNode.x) || 0) + 280;
            const nextY = (Number(lastNode.y) || 0) + ((flowNodes.length % 2) * 36);

            return {
                x: Math.max(24, nextX),
                y: Math.max(24, nextY),
            };
        }

        const index = flowNodes.length;
        return {
            x: 24 + ((index % 3) * 300),
            y: 24 + (Math.floor(index / 3) * 240),
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
        if (node?.type === 'send_template') {
            return getTemplateById(node.templateId || '')?.content || 'Pilih template WhatsApp yang sudah di-approve Meta.';
        }

        if (node?.type === 'send_email') {
            return `To: ${node.emailTo || '-'} | Subject: ${node.emailSubject || '-'}`;
        }

        if (node?.type === 'chat_to_agent') {
            const agent = getAgentById(node.agentId || '');
            return `Agent: ${agent?.name || '-'} | Team: ${agent?.team || '-'}`;
        }

        if (node?.type === 'call_to_agent') {
            const agent = getAgentById(node.agentId || '');
            return `Call Agent: ${agent?.name || '-'} | Team: ${agent?.team || '-'}`;
        }

        if (node?.type === 'send_location') {
            return `Lokasi: ${node.locationName || 'Belum dipilih'}`;
        }

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
        const optionConfig = getOptionNodeConfig(node.type);
        if (!optionConfig || !node.options?.length) {
            return '';
        }

        const optionTypeLabel = optionConfig.label.replace(/\s+Options?$/i, '').replace(/\s+Paths?$/i, '') || 'Option';
        const isReadOnlyOptions = node.type === 'send_survey';

        return `
            <div class="node-buttons">
                ${node.options.map((label, index) => `
                    <div class="node-option-item">
                        <span class="node-button-pill">${escapeHtml(label)}</span>
                        ${isReadOnlyOptions ? '' : `
                            <button
                                class="link-handle"
                                type="button"
                                data-link-start="${node.id}"
                                data-link-key="${node.id}::${index}"
                                data-link-index="${index}"
                                aria-label="Hubungkan ${escapeHtml(label || `${optionTypeLabel} ${index + 1}`)} dari ${escapeHtml(node.title)}"
                            >→</button>
                        `}
                    </div>
                `).join('')}
            </div>
        `;
    }

    function getNodeOutputHandles(node) {
        if (!node) return [];

        if (['end', 'send_survey'].includes(node.type)) {
            return [];
        }

        if (getOptionNodeConfig(node.type)) {
            const optionHandles = (node.options || []).map((label, index) => ({
                key: `${node.id}::${index}`,
                label: label || `Option ${index + 1}`,
            }));

            if (optionHandles.length) {
                return optionHandles;
            }

            return [{
                key: node.id,
                label: 'Default Path',
            }];
        }

        return [{
            key: node.id,
            label: node.title || 'Node',
        }];
    }

    function renderNodeHandles(node) {
        if (getOptionNodeConfig(node?.type) && (node.options || []).length) {
            return '';
        }

        const handles = getNodeOutputHandles(node);
        if (!handles.length) {
            return '';
        }

        return `
            <div class="node-output-handles">
                ${handles.map((handle, index) => `
                    <button
                        class="link-handle"
                        type="button"
                        data-link-start="${node.id}"
                        data-link-key="${handle.key}"
                        data-link-index="${index}"
                        aria-label="Hubungkan ${escapeHtml(handle.label)} dari ${escapeHtml(node.title)}"
                    >→</button>
                `).join('')}
            </div>
        `;
    }

    function getAddNodeMarkup({ isBranch = false, targetId = '', targetKind = 'root' } = {}) {
        return `
            <div class="add-node ${isBranch ? 'branch-add' : ''}" data-add-kind="${targetKind}" data-add-target="${escapeHtml(targetId)}">
                <button class="add-response" type="button" data-action="open-menu"><span class="plus">+</span> Add Node</button>
                <div class="menu">
                    ${menuOptionsMarkup}
                </div>
            </div>
        `;
    }

    function getButtonBranchesPreview(node, branchFlowNumber) {
        if (!['button', 'branch'].includes(node.type) || !node.branchFlows?.length) {
            return '';
        }

        const branchItemLabel = node.type === 'branch' ? 'Path' : 'Button';
        const branchCopyFallback = node.type === 'branch'
            ? 'Lanjutan flow untuk cabang'
            : 'Text lanjutan untuk tombol';

        return `
            <div class="button-branches ${node.branchFlows.length > 1 ? 'two' : ''}">
                ${node.branchFlows.map((branch) => `
                    <div class="button-branch">
                        <span class="button-branch-label">${escapeHtml(branch.label || branchItemLabel)}</span>
                        <div class="button-branch-node" data-edit-id="${branch.id}">
                            <span class="node-pill">Branch ${branchFlowNumber}</span>
                            <div class="button-branch-title">${escapeHtml(branch.title || 'Text Message')}</div>
                            <div class="button-branch-copy">
                                ${escapeHtml(branch.body || `${branchCopyFallback} "${branch.label || branchItemLabel}"`)}
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
        const canOpenEditor = isMessageDrawerNode(node);

        if (node.type === 'end') {
            return `
                <div class="canvas-node ${activeDrawerTargetId === node.id ? 'active' : ''}" data-node-id="${node.id}" data-node-type="end" style="left:${Number(node.x) || 0}px; top:${Number(node.y) || 0}px; width:132px;">
                    <div class="end-node-card">
                        <span class="link-target" aria-hidden="true"></span>
                        <span>End</span>
                        <button class="remove-node" type="button" title="Hapus flow" data-remove-node="${node.id}">x</button>
                    </div>
                </div>
            `;
        }

        return `
            <div class="canvas-node ${activeDrawerTargetId === node.id ? 'active' : ''}" data-node-id="${node.id}" data-node-type="${escapeHtml(node.type || '')}" style="left:${Number(node.x) || 0}px; top:${Number(node.y) || 0}px;">
            <div class="bot-node" data-edit-id="${node.id}">
                <span class="link-target" aria-hidden="true"></span>
                <header>
                    <div class="bot-node-title">
                        <span class="node-pill">Node ${flowNumber}</span>
                        <h2>${escapeHtml(node.title)}</h2>
                    </div>
                    <button class="remove-node" type="button" title="Hapus flow" data-remove-node="${node.id}">x</button>
                </header>
                <div class="node-content">
                    ${getHeaderPreview(node)}
                    <div class="node-message">${escapeHtml(getNodeMessage(node))}</div>
                    ${getButtonsPreview(node)}
                    ${getFallbackPreview(node)}
                    ${canOpenEditor ? `
                        <div class="node-actions">
                            <button class="node-view-button" type="button" data-open-editor="${node.id}">Lihat</button>
                        </div>
                    ` : ''}
                </div>
                ${renderNodeHandles(node)}
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
        syncPanelPreviewFromInputs();
    }

    function renderOptionsEditor(options = [], type = 'button') {
        const optionConfig = getOptionNodeConfig(type) || getOptionNodeConfig('button');
        const placeholder = optionConfig.placeholder;
        optionsEditorList.innerHTML = options.map((label, index) => `
            <div class="option-item">
                <input class="drawer-input" data-option-input="${index}" maxlength="40" placeholder="${placeholder}" value="${escapeHtml(label)}">
                <button class="button-remove" type="button" data-remove-button="${index}">&times;</button>
            </div>
        `).join('');

        const limit = optionConfig.limit;
        addOptionButton.disabled = options.length >= limit;
        addOptionButton.style.opacity = options.length >= limit ? '.5' : '1';
        optionsEditorLabel.textContent = optionConfig.label;
        addOptionButton.textContent = optionConfig.addLabel;
    }

    function renderTemplateSelector(selectedTemplateId = '') {
        const activeTemplate = getTemplateById(selectedTemplateId);
        templateSelector.innerHTML = TEMPLATE_LIBRARY.map((template) => `
            <option value="${escapeHtml(template.id)}" ${template.id === activeTemplate?.id ? 'selected' : ''}>
                ${escapeHtml(template.name)} - ${escapeHtml(template.category)}
            </option>
        `).join('');

        templateSelectorHelp.textContent = activeTemplate
            ? `${activeTemplate.category}: ${activeTemplate.content}`
            : 'Pilih template yang sudah di-approve Meta.';
    }

    function renderAgentSelector(selectedAgentId = '') {
        const activeAgent = getAgentById(selectedAgentId);
        agentSelector.innerHTML = AGENT_LIBRARY.map((agent) => `
            <option value="${escapeHtml(agent.id)}" ${agent.id === activeAgent?.id ? 'selected' : ''}>
                ${escapeHtml(agent.name)} - ${escapeHtml(agent.team)}
            </option>
        `).join('');

        agentSelectorHelp.textContent = activeAgent
            ? `${activeAgent.team}: ${activeAgent.note}`
            : 'Pilih agent tujuan untuk menerima percakapan.';
    }

    function formatFileSize(bytes) {
        const size = Number(bytes) || 0;
        if (!size) return '';
        if (size < 1024) return `${size} B`;
        if (size < 1024 * 1024) return `${(size / 1024).toFixed(1)} KB`;
        return `${(size / (1024 * 1024)).toFixed(1)} MB`;
    }

    function renderAttachmentSelection(kind) {
        const isHeader = kind === 'header';
        const attachment = isHeader ? headerImageAttachment : mediaAttachment;
        const selected = isHeader ? headerImageSelected : mediaSourceSelected;
        const fileName = isHeader ? headerImageFileName : mediaSourceFileName;
        const fileMeta = isHeader ? headerImageFileMeta : mediaSourceFileMeta;

        selected.classList.toggle('active', Boolean(attachment.name));
        fileName.textContent = attachment.name || '';
        fileMeta.textContent = [attachment.type, formatFileSize(attachment.size)].filter(Boolean).join(' - ');
    }

    function resetAttachment(kind) {
        if (kind === 'header') {
            headerImageAttachment = { name: '', type: '', size: 0, dataUrl: '' };
            drawerHeaderImage.value = '';
        } else {
            mediaAttachment = { name: '', type: '', size: 0, dataUrl: '' };
            mediaSourceInput.value = '';
        }

        renderAttachmentSelection(kind);
        syncPanelPreviewFromInputs();
    }

    function isFileAccepted(file, accept) {
        if (!accept) return false;

        return accept.split(',').some((rule) => {
            const normalizedRule = rule.trim().toLowerCase();
            const fileType = (file.type || '').toLowerCase();
            const fileName = file.name.toLowerCase();

            if (normalizedRule.endsWith('/*')) {
                return fileType.startsWith(normalizedRule.slice(0, -1));
            }

            if (normalizedRule.startsWith('.')) {
                return fileName.endsWith(normalizedRule);
            }

            return fileType === normalizedRule;
        });
    }

    function readAttachment(file, kind) {
        if (!file) return;

        const activeMediaType = getMediaTypeById(mediaTypeSelector.value);
        const accept = kind === 'header' ? 'image/*' : activeMediaType?.accept;

        if (!isFileAccepted(file, accept)) {
            if (kind === 'header') {
                drawerHeaderImage.value = '';
            } else {
                mediaSourceInput.value = '';
            }
            alert(kind === 'header'
                ? 'File header harus berupa gambar.'
                : `File tidak sesuai dengan tipe media ${activeMediaType?.name || 'yang dipilih'}.`);
            return;
        }

        const reader = new FileReader();
        reader.addEventListener('load', () => {
            const attachment = {
                name: file.name,
                type: file.type || 'application/octet-stream',
                size: file.size,
                dataUrl: String(reader.result || ''),
            };

            if (kind === 'header') {
                headerImageAttachment = attachment;
            } else {
                mediaAttachment = attachment;
            }

            renderAttachmentSelection(kind);
            syncPanelPreviewFromInputs();
        });
        reader.readAsDataURL(file);
    }

    function bindAttachmentDropzone(dropzone, input, kind) {
        input.addEventListener('change', () => {
            readAttachment(input.files?.[0], kind);
        });

        ['dragenter', 'dragover'].forEach((eventName) => {
            dropzone.addEventListener(eventName, (event) => {
                event.preventDefault();
                if (!input.disabled) dropzone.classList.add('dragging');
            });
        });

        ['dragleave', 'drop'].forEach((eventName) => {
            dropzone.addEventListener(eventName, (event) => {
                event.preventDefault();
                dropzone.classList.remove('dragging');
            });
        });

        dropzone.addEventListener('drop', (event) => {
            if (input.disabled) return;
            readAttachment(event.dataTransfer?.files?.[0], kind);
        });
    }

    function renderMediaTypeSelector(selectedMediaType = '') {
        const activeMediaType = getMediaTypeById(selectedMediaType);
        mediaTypeSelector.innerHTML = MEDIA_TYPE_LIBRARY.map((mediaType) => `
            <option value="${escapeHtml(mediaType.id)}" ${mediaType.id === activeMediaType?.id ? 'selected' : ''}>
                ${escapeHtml(mediaType.name)}
            </option>
        `).join('');

        const hasMediaType = Boolean(activeMediaType?.id);
        mediaSourceInput.disabled = !hasMediaType;
        mediaSourceInput.accept = activeMediaType?.accept || '';
        mediaSourceDropzone.classList.toggle('disabled', !hasMediaType);
        mediaDropzoneTitle.textContent = hasMediaType
            ? `Drop file ${activeMediaType.name.toLowerCase()} di sini`
            : 'Pilih tipe media terlebih dahulu';
        mediaDropzoneText.textContent = hasMediaType
            ? 'atau klik untuk memilih attachment dari perangkat'
            : 'Setelah itu drop file di sini atau klik untuk memilih attachment';
        mediaSourceHelp.textContent = activeMediaType?.id
            ? `Format file dibatasi sesuai tipe ${activeMediaType.name}.`
            : 'Pilih tipe media terlebih dahulu';
    }

    function renderMessagePreview(entity, values = {}) {
        if (!flowMessagePreview) return;

        if (!entity) {
            flowMessagePreview.innerHTML = `
                <div class="wa-message">Pilih flow untuk melihat preview<span class="wa-message-time">09:41</span></div>
            `;
            return;
        }

        const headerType = values.headerType ?? entity.headerType ?? 'text';
        const headerText = values.headerText ?? entity.headerText ?? '';
        const headerImage = values.headerImage ?? entity.headerImage ?? '';
        const emailTo = values.emailTo ?? entity.emailTo ?? '';
        const emailCc = values.emailCc ?? entity.emailCc ?? '';
        const emailSubject = values.emailSubject ?? entity.emailSubject ?? '';
        const mediaType = values.mediaType ?? entity.mediaType ?? '';
        const mediaSource = values.mediaSource ?? entity.mediaSource ?? '';
        const locationName = values.locationName ?? entity.locationName ?? '';
        const activeAgent = ['chat_to_agent', 'call_to_agent'].includes(entity.type)
            ? getAgentById(values.agentId ?? entity.agentId ?? '')
            : null;
        const activeTemplate = entity.type === 'send_template'
            ? getTemplateById(values.templateId ?? entity.templateId ?? '')
            : null;
        const activeMediaType = entity.type === 'send_media'
            ? getMediaTypeById(mediaType)
            : null;
        const body = entity.type === 'send_template'
            ? (activeTemplate?.content || 'Pilih template untuk melihat preview.')
            : (values.body ?? entity.body ?? getNodeMessage(entity));
        const options = values.options ?? entity.options ?? [];
        const headerMarkup = headerType === 'image' && headerImage
            ? `<div class="wa-message-header"><img src="${escapeHtml(headerImage)}" alt="Header image"></div>`
            : (headerType === 'text' && headerText ? `<div class="wa-message-header">${escapeHtml(headerText)}</div>` : '');
        const optionsMarkup = getOptionNodeConfig(entity.type) && options.length
            ? `<div class="wa-options">${options.map((label) => `<div class="wa-option">${escapeHtml(label)}</div>`).join('')}</div>`
            : '';

        if (entity.type === 'send_email') {
            flowMessagePreview.innerHTML = `
                <div class="wa-message">
                    <div><strong>To:</strong> ${escapeHtml(emailTo || '-')}</div>
                    <div><strong>CC:</strong> ${escapeHtml(emailCc || '-')}</div>
                    <div><strong>Subject:</strong> ${escapeHtml(emailSubject || '-')}</div>
                    <div style="margin-top:10px;">${escapeHtml(body || 'Tulis body email untuk melihat preview.')}</div>
                    <span class="wa-message-time">Email</span>
                </div>
            `;
            return;
        }

        if (['chat_to_agent', 'call_to_agent'].includes(entity.type)) {
            flowMessagePreview.innerHTML = `
                <div class="wa-message">
                    <div><strong>Mode:</strong> ${escapeHtml(entity.type === 'call_to_agent' ? 'Call To Agent' : 'Chat to Agent')}</div>
                    <div><strong>Agent:</strong> ${escapeHtml(activeAgent?.name || '-')}</div>
                    <div><strong>Team:</strong> ${escapeHtml(activeAgent?.team || '-')}</div>
                    <div style="margin-top:10px;">${escapeHtml(activeAgent?.note || body || 'Pilih agent untuk melihat preview.')}</div>
                    <span class="wa-message-time">Agent</span>
                </div>
            `;
            return;
        }

        if (entity.type === 'send_media') {
            flowMessagePreview.innerHTML = `
                <div class="wa-message">
                    <div><strong>Tipe Media:</strong> ${escapeHtml(activeMediaType?.name || '-')}</div>
                    <div><strong>Source:</strong> ${escapeHtml(mediaSource || 'Belum dipilih')}</div>
                    <div style="margin-top:10px;">${escapeHtml(values.body ?? entity.body ?? 'Tambahkan deskripsi media.')}</div>
                    <span class="wa-message-time">Media</span>
                </div>
            `;
            return;
        }

        if (entity.type === 'send_location') {
            flowMessagePreview.innerHTML = `
                <div class="wa-message">
                    <div><strong>Nama Lokasi:</strong> ${escapeHtml(locationName || 'Belum dipilih')}</div>
                    <div style="margin-top:10px;">Klik "Cari Lokasi" untuk memilih lokasi yang akan dikirim ke pelanggan.</div>
                    <span class="wa-message-time">Location</span>
                </div>
            `;
            return;
        }

        flowMessagePreview.innerHTML = `
            <div class="wa-message">
                ${headerMarkup}
                <div>${escapeHtml(body || 'Tulis isi pesan untuk melihat preview.')}</div>
                ${optionsMarkup}
                <span class="wa-message-time">09:41</span>
            </div>
        `;
    }

    function syncPanelPreviewFromInputs() {
        const entity = activeDrawerTargetId ? findFlowEntityById(activeDrawerTargetId) : null;
        if (!entity) {
            renderMessagePreview(null);
            return;
        }

        const headerType = document.querySelector('[data-header-type].active')?.dataset.headerType || 'text';
        renderMessagePreview(entity, {
            headerType,
            headerText: drawerHeaderText.value.trim(),
            headerImage: headerImageAttachment.dataUrl,
            body: drawerBodyText.value.trim(),
            emailTo: emailToInput.value.trim(),
            emailCc: emailCcInput.value.trim(),
            emailSubject: emailSubjectInput.value.trim(),
            agentId: agentSelector.value,
            mediaType: mediaTypeSelector.value,
            mediaSource: mediaAttachment.name,
            locationName: locationNameInput.value.trim(),
            templateId: templateSelector.value,
            options: getOptionNodeConfig(entity.type)
                ? getDrawerOptionValues(getOptionNodeConfig(entity.type).limit)
                : [],
        });
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
        setFlowEditorPanelOpen(true);
        flowEditorPanel.classList.add('has-selection');
        messageDrawerTitle.textContent =
            entity.label ? `Flow ${entity.label}` : (entity.title || 'Flow Node');
        drawerHeaderText.value = entity.headerText || '';
        headerImageAttachment = {
            name: entity.headerImageName || '',
            type: entity.headerImageType || '',
            size: entity.headerImageSize || 0,
            dataUrl: entity.headerImage || '',
        };
        drawerHeaderImage.value = '';
        renderAttachmentSelection('header');
        drawerBodyText.value = entity.body || '';
        drawerFallbackText.value = entity.fallback || '';
        emailToInput.value = entity.emailTo || '';
        emailCcInput.value = entity.emailCc || '';
        emailSubjectInput.value = entity.emailSubject || '';
        renderAgentSelector(entity.agentId || '');
        renderMediaTypeSelector(entity.mediaType || '');
        mediaAttachment = {
            name: entity.mediaSource || '',
            type: entity.mediaMimeType || '',
            size: entity.mediaSize || 0,
            dataUrl: entity.mediaDataUrl || '',
        };
        mediaSourceInput.value = '';
        renderAttachmentSelection('media');
        locationNameInput.value = entity.locationName || '';
        renderTemplateSelector(entity.templateId || '');
        setDrawerHeaderType(entity.headerType || 'text');
        const isTemplateNode = entity.type === 'send_template';
        const isEmailNode = entity.type === 'send_email';
        const isAgentNode = ['chat_to_agent', 'call_to_agent'].includes(entity.type);
        const isMediaNode = entity.type === 'send_media';
        const isLocationNode = entity.type === 'send_location';
        const isRequestLocationNode = entity.type === 'request_location';
        headerSection?.classList.toggle('hidden', isTemplateNode || isRequestLocationNode);
        headerTextField.classList.toggle('hidden', isTemplateNode || isEmailNode || isAgentNode || isMediaNode || isLocationNode || isRequestLocationNode || (entity.headerType || 'text') !== 'text');
        headerImageField.classList.toggle('hidden', isTemplateNode || isEmailNode || isAgentNode || isMediaNode || isLocationNode || isRequestLocationNode || (entity.headerType || 'text') !== 'image');
        drawerBodyText.closest('.drawer-field')?.classList.toggle('hidden', isTemplateNode || isAgentNode || isLocationNode);
        drawerBodyText.previousElementSibling.textContent = isEmailNode ? 'Email Body' : (isMediaNode ? 'Deskripsi Media' : (isRequestLocationNode ? 'Isi Pesan' : 'Body'));
        drawerBodyText.placeholder = isEmailNode ? 'Tulis isi email di sini' : (isMediaNode ? 'Tulis deskripsi media di sini' : (isRequestLocationNode ? 'Isi Pesan' : 'Tulis isi pesan di sini'));
        drawerFallbackText.closest('.drawer-field')?.classList.toggle('hidden', isTemplateNode || isEmailNode || isAgentNode || isMediaNode || isLocationNode || isRequestLocationNode);
        emailToField.classList.toggle('hidden', !isEmailNode);
        emailCcField.classList.toggle('hidden', !isEmailNode);
        emailSubjectField.classList.toggle('hidden', !isEmailNode);
        agentSelectorField.classList.toggle('hidden', !isAgentNode);
        mediaTypeField.classList.toggle('hidden', !isMediaNode);
        mediaSourceField.classList.toggle('hidden', !isMediaNode);
        locationNameField.classList.toggle('hidden', !isLocationNode);
        locationSearchField.classList.toggle('hidden', !isLocationNode);
        requestLocationInfoField.classList.toggle('hidden', !isRequestLocationNode);
        templateSelectorField.classList.toggle('hidden', !isTemplateNode);
        optionsEditorField.classList.toggle('hidden', !getOptionNodeConfig(entity.type));
        if (getOptionNodeConfig(entity.type)) {
            renderOptionsEditor(entity.options || [], entity.type);
        } else {
            optionsEditorList.innerHTML = '';
        }
        renderMessagePreview(entity);
        drawerBackdrop.classList.remove('open');
        textMessageDrawer.classList.remove('open');
        textMessageDrawer.setAttribute('aria-hidden', 'false');
    }

    function closeTextMessageDrawer() {
        activeDrawerTargetId = null;
        setFlowEditorPanelOpen(false);
        flowEditorPanel.classList.remove('has-selection');
        renderMessagePreview(null);
        drawerBackdrop.classList.remove('open');
        textMessageDrawer.classList.remove('open');
        textMessageDrawer.setAttribute('aria-hidden', 'false');
    }

    function saveTextMessageNode() {
        const activeHeaderType = document.querySelector('[data-header-type].active')?.dataset.headerType || 'text';
        if (!activeDrawerTargetId) return;

        const entity = findFlowEntityById(activeDrawerTargetId);
        if (!entity) return;

        entity.headerType = activeHeaderType;
        entity.headerText = drawerHeaderText.value.trim();
        entity.headerImage = headerImageAttachment.dataUrl;
        entity.headerImageName = headerImageAttachment.name;
        entity.headerImageType = headerImageAttachment.type;
        entity.headerImageSize = headerImageAttachment.size;
        entity.body = entity.type === 'send_template' ? '' : drawerBodyText.value.trim();
        entity.fallback = entity.type === 'send_template' ? '' : drawerFallbackText.value.trim();
        entity.emailTo = entity.type === 'send_email' ? emailToInput.value.trim() : (entity.emailTo || '');
        entity.emailCc = entity.type === 'send_email' ? emailCcInput.value.trim() : (entity.emailCc || '');
        entity.emailSubject = entity.type === 'send_email' ? emailSubjectInput.value.trim() : (entity.emailSubject || '');
        entity.agentId = ['chat_to_agent', 'call_to_agent'].includes(entity.type) ? agentSelector.value : (entity.agentId || '');
        entity.mediaType = entity.type === 'send_media' ? mediaTypeSelector.value : (entity.mediaType || '');
        entity.mediaSource = entity.type === 'send_media' ? mediaAttachment.name : (entity.mediaSource || '');
        entity.mediaMimeType = entity.type === 'send_media' ? mediaAttachment.type : (entity.mediaMimeType || '');
        entity.mediaSize = entity.type === 'send_media' ? mediaAttachment.size : (entity.mediaSize || 0);
        entity.mediaDataUrl = entity.type === 'send_media' ? mediaAttachment.dataUrl : (entity.mediaDataUrl || '');
        entity.locationName = entity.type === 'send_location' ? locationNameInput.value.trim() : (entity.locationName || '');
        if (entity.type === 'send_template') {
            const activeTemplate = getTemplateById(templateSelector.value);
            entity.templateId = activeTemplate?.id || '';
            entity.headerType = 'text';
            entity.headerText = activeTemplate?.name || 'Template Resmi';
        }
        if (entity.type === 'send_email') {
            entity.headerType = 'text';
            entity.headerText = entity.emailSubject || 'Email Notification';
            entity.fallback = '';
        }
        if (['chat_to_agent', 'call_to_agent'].includes(entity.type)) {
            const activeAgent = getAgentById(entity.agentId);
            entity.headerType = 'text';
            entity.headerText = activeAgent?.name || (entity.type === 'call_to_agent' ? 'Hubungi Agent' : 'Alihkan ke Agent');
            entity.body = activeAgent?.note || '';
            entity.fallback = '';
        }
        if (entity.type === 'send_media') {
            const activeMediaType = getMediaTypeById(entity.mediaType);
            entity.headerType = 'text';
            entity.headerText = activeMediaType?.name || 'Media';
            entity.fallback = '';
        }
        if (entity.type === 'send_location') {
            entity.headerType = 'text';
            entity.headerText = entity.locationName || 'Lokasi Kami';
            entity.body = '';
            entity.fallback = '';
        }
        if (getOptionNodeConfig(entity.type)) {
            entity.options = getDrawerOptionValues(getOptionNodeConfig(entity.type).limit);
            if (['button', 'list', 'branch'].includes(entity.type)) {
                syncButtonBranches(entity);
            }
            pruneConnectionsForNode(entity);
        }
        entity.message = entity.type === 'send_template'
            ? (getTemplateById(entity.templateId)?.content || defaultMessage(entity.title))
            : (entity.body || defaultMessage(entity.title));

        renderFlowNodes();
        openFlowEditor(entity.id);
    }

    function renderFlowNodes() {
        botNodes.innerHTML = flowNodes.map((node, index) => renderNodeCard(node, index + 1)).join('');
        requestAnimationFrame(() => renderFlowConnections());
    }

    function getLinkHandlePosition(sourceKey) {
        const handle = sourceKey === 'start'
            ? document.querySelector('[data-link-start="start"]')
            : document.querySelector(`.link-handle[data-link-key="${sourceKey}"]`);
        if (!handle || !builderStage) return null;

        const handleRect = handle.getBoundingClientRect();
        const stageRect = builderStage.getBoundingClientRect();
        return {
            x: ((handleRect.left + handleRect.width / 2) - stageRect.left) / zoomLevel,
            y: ((handleRect.top + handleRect.height / 2) - stageRect.top) / zoomLevel,
        };
    }

    function getNodeInputPosition(targetId) {
        const target = document.querySelector(`.canvas-node[data-node-id="${targetId}"] .link-target`)
            || document.querySelector(`.canvas-node[data-node-id="${targetId}"] .bot-node`);
        if (!target || !builderStage) return null;

        const nodeRect = target.getBoundingClientRect();
        const stageRect = builderStage.getBoundingClientRect();
        return {
            x: ((nodeRect.left + nodeRect.width / 2) - stageRect.left) / zoomLevel,
            y: ((nodeRect.top + (nodeRect.height / 2)) - stageRect.top) / zoomLevel,
        };
    }

    function buildConnectionPath(from, to) {
        const delta = Math.max(60, Math.abs(to.x - from.x) * 0.45);
        return `M ${from.x} ${from.y} C ${from.x + delta} ${from.y}, ${to.x - delta} ${to.y}, ${to.x} ${to.y}`;
    }

    function renderFlowConnections(previewPoint = null) {
        if (!flowLinks || !builderStage) return;

        const stageWidth = builderStage.scrollWidth;
        const stageHeight = builderStage.scrollHeight;
        flowLinks.setAttribute('viewBox', `0 0 ${stageWidth} ${stageHeight}`);
        flowLinks.setAttribute('width', String(stageWidth));
        flowLinks.setAttribute('height', String(stageHeight));

        const connectionMarkup = flowConnections.map((connection) => {
            const from = getLinkHandlePosition(connection.fromKey || connection.from);
            const to = getNodeInputPosition(connection.to);
            if (!from || !to) return '';

            return `<path d="${buildConnectionPath(from, to)}" fill="none" stroke="#3d6fd6" stroke-width="2.5" stroke-linecap="round" marker-end="url(#flowArrowHead)"></path>`;
        }).join('');

        const previewMarkup = activeLinkDrag && previewPoint
            ? (() => {
                const from = getLinkHandlePosition(activeLinkDrag.fromKey || activeLinkDrag.from);
                if (!from) return '';
                return `<path d="${buildConnectionPath(from, previewPoint)}" fill="none" stroke="#7da6ea" stroke-width="2.5" stroke-dasharray="6 6" stroke-linecap="round" marker-end="url(#flowArrowHead)"></path>`;
            })()
            : '';

        flowLinks.innerHTML = `
            <defs>
                <marker id="flowArrowHead" markerWidth="8" markerHeight="8" refX="6" refY="4" orient="auto">
                    <path d="M0,0 L8,4 L0,8 z" fill="#3d6fd6"></path>
                </marker>
            </defs>
            ${connectionMarkup}
            ${previewMarkup}
        `;
    }

    function pruneConnectionsForNode(node) {
        const validHandleKeys = new Set(getNodeOutputHandles(node).map((handle) => handle.key));
        flowConnections = flowConnections.filter((connection) => {
            if ((connection.fromNodeId || '') !== node.id) {
                return true;
            }

            return validHandleKeys.has(connection.fromKey || connection.from);
        });
    }

    function removeConnectionsForNode(nodeId) {
        flowConnections = flowConnections.filter((connection) => {
            const sourceNodeId = connection.fromNodeId || connection.from;
            return sourceNodeId !== nodeId && connection.to !== nodeId;
        });
    }

    function updateConnectionForSource(fromKey, fromNodeId, toId) {
        flowConnections = flowConnections.filter((connection) => (connection.fromKey || connection.from) !== fromKey);
        flowConnections.push({ fromKey, fromNodeId, to: toId });
        renderFlowConnections();
    }

    function addFlowNode(type, target = { kind: 'root', id: '' }) {
        const node = createNode(type);
        if (['button', 'list', 'branch'].includes(node.type)) {
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

    createInteractiveButton.addEventListener('click', startCreateInteractive);

    interactiveSearchInput.addEventListener('input', (event) => {
        const keyword = event.target.value.trim().toLowerCase();

        interactiveTableBody.querySelectorAll('tr').forEach((row) => {
            row.style.display = row.textContent.toLowerCase().includes(keyword) ? '' : 'none';
        });
    });

    interactiveTableBody.addEventListener('click', (event) => {
        const sessionButton = event.target.closest('[aria-label="Edit Session"]');
        if (!sessionButton) return;

        activeSessionRow = sessionButton.closest('tr');
        sessionCountInput.value = activeSessionRow?.querySelector('.session-count-cell')?.dataset.sessionCount || '';
        showSessionSettings();
    });

    sessionBackButton.addEventListener('click', showInteractiveList);
    sessionSaveButton.addEventListener('click', () => {
        if (!Number(sessionCountInput.value)) {
            alert('Jumlah session wajib diisi.');
            sessionCountInput.focus();
            return;
        }

        if (activeSessionRow) {
            syncInteractiveSessionCount(activeSessionRow, sessionCountInput.value);
        }
        showSessionReview();
    });
    sessionTimeoutMessage.addEventListener('input', syncSessionTimeoutPreview);
    paySessionAdsButton.addEventListener('click', () => {
        alert('Iklan siap dibayar dan dikirim.');
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

    [drawerHeaderText, drawerBodyText, drawerFallbackText].forEach((field) => {
        field.addEventListener('input', syncPanelPreviewFromInputs);
    });

    [emailToInput, emailCcInput, emailSubjectInput].forEach((field) => {
        field.addEventListener('input', syncPanelPreviewFromInputs);
    });

    bindAttachmentDropzone(headerImageDropzone, drawerHeaderImage, 'header');
    bindAttachmentDropzone(mediaSourceDropzone, mediaSourceInput, 'media');
    removeHeaderImage.addEventListener('click', () => resetAttachment('header'));
    removeMediaSource.addEventListener('click', () => resetAttachment('media'));

    [locationNameInput].forEach((field) => {
        field.addEventListener('input', syncPanelPreviewFromInputs);
    });

    templateSelector.addEventListener('change', () => {
        renderTemplateSelector(templateSelector.value);
        syncPanelPreviewFromInputs();
    });

    agentSelector.addEventListener('change', () => {
        renderAgentSelector(agentSelector.value);
        syncPanelPreviewFromInputs();
    });

    mediaTypeSelector.addEventListener('change', () => {
        resetAttachment('media');
        renderMediaTypeSelector(mediaTypeSelector.value);
        syncPanelPreviewFromInputs();
    });

    locationSearchButton.addEventListener('click', () => {
        locationSearchHelp.textContent = locationNameInput.value.trim()
            ? `Lokasi "${locationNameInput.value.trim()}" siap dipilih.`
            : 'Masukkan nama lokasi terlebih dahulu.';
        syncPanelPreviewFromInputs();
    });

    document.querySelectorAll('[data-drawer-close]').forEach((button) => {
        button.addEventListener('click', closeTextMessageDrawer);
    });

    drawerBackdrop.addEventListener('click', closeTextMessageDrawer);
    flowEditorBackdrop.addEventListener('click', closeTextMessageDrawer);
    document.getElementById('saveTextMessage').addEventListener('click', saveTextMessageNode);
    addOptionButton.addEventListener('click', () => {
        const entity = activeDrawerTargetId ? findFlowEntityById(activeDrawerTargetId) : null;
        const type = entity?.type || 'button';
        const optionConfig = getOptionNodeConfig(type) || getOptionNodeConfig('button');
        const limit = optionConfig.limit;
        const options = getDrawerOptionValues(limit);
        if (options.length >= limit) return;

        options.push('');
        renderOptionsEditor(options, type);
        syncPanelPreviewFromInputs();
    });

    optionsEditorList.addEventListener('input', syncPanelPreviewFromInputs);

    optionsEditorList.addEventListener('click', (event) => {
        const removeButton = event.target.closest('[data-remove-button]');
        if (!removeButton) return;

        const entity = activeDrawerTargetId ? findFlowEntityById(activeDrawerTargetId) : null;
        const type = entity?.type || 'button';
        const optionConfig = getOptionNodeConfig(type) || getOptionNodeConfig('button');
        const limit = optionConfig.limit;
        const options = getDrawerOptionValues(limit).filter((_, index) => index !== Number(removeButton.dataset.removeButton));
        renderOptionsEditor(options, type);
        syncPanelPreviewFromInputs();
    });

    flowToolList?.addEventListener('click', (event) => {
        const tool = event.target.closest('.flow-tool[data-node]');
        if (!tool) return;

        event.preventDefault();
        addFlowNode(tool.dataset.node || 'Send Text');
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

    builderStage.addEventListener('pointerdown', (event) => {
        const handle = event.target.closest('.link-handle[data-link-start]');
        if (!handle) return;

        event.preventDefault();
        event.stopPropagation();
        activeLinkDrag = {
            fromKey: handle.dataset.linkKey || handle.dataset.linkStart || '',
            fromNodeId: handle.dataset.linkStart || '',
            pointerId: event.pointerId,
        };
        builderStage.setPointerCapture(event.pointerId);
    });

    builderStage.addEventListener('pointermove', (event) => {
        if (!activeLinkDrag || activeLinkDrag.pointerId !== event.pointerId) return;

        const stageRect = builderStage.getBoundingClientRect();
        renderFlowConnections({
            x: (event.clientX - stageRect.left) / zoomLevel,
            y: (event.clientY - stageRect.top) / zoomLevel,
        });
    });

    function stopLinkDragging(event) {
        if (!activeLinkDrag || activeLinkDrag.pointerId !== event.pointerId) return;

        const dropTarget = document.elementFromPoint(event.clientX, event.clientY);
        const targetPort = dropTarget?.closest('.link-target');
        const fallbackNode = !targetPort
            ? dropTarget?.closest('.canvas-node[data-node-id]')
            : null;
        const targetNode = targetPort?.closest('.canvas-node[data-node-id]') || fallbackNode;
        const targetId = targetNode?.dataset.nodeId || '';

        if (targetId && targetId !== activeLinkDrag.fromNodeId) {
            updateConnectionForSource(activeLinkDrag.fromKey, activeLinkDrag.fromNodeId, targetId);
        } else {
            renderFlowConnections();
        }

        if (builderStage.hasPointerCapture(event.pointerId)) {
            builderStage.releasePointerCapture(event.pointerId);
        }
        activeLinkDrag = null;
    }

    builderStage.addEventListener('pointerup', stopLinkDragging);
    builderStage.addEventListener('pointercancel', stopLinkDragging);

    botNodes.addEventListener('pointerdown', (event) => {
        const nodeElement = event.target.closest('.canvas-node[data-node-id]');
        if (!nodeElement) return;
        if (event.target.closest('button, input, textarea, select, .menu, a, label')) return;

        const entity = findFlowEntityById(nodeElement.dataset.nodeId);
        if (!entity) return;

        activeDragNodeId = entity.id;
        dragPointerId = event.pointerId;
        dragStartX = event.clientX;
        dragStartY = event.clientY;
        dragNodeOriginX = Number(entity.x) || 0;
        dragNodeOriginY = Number(entity.y) || 0;
        nodeElement.classList.add('dragging');
        botNodes.setPointerCapture(event.pointerId);
    });

    botNodes.addEventListener('pointermove', (event) => {
        if (!activeDragNodeId || dragPointerId !== event.pointerId) return;

        const entity = findFlowEntityById(activeDragNodeId);
        const nodeElement = botNodes.querySelector(`.canvas-node[data-node-id="${activeDragNodeId}"]`);
        if (!entity || !nodeElement) return;

        entity.x = Math.max(0, dragNodeOriginX + ((event.clientX - dragStartX) / zoomLevel));
        entity.y = Math.max(0, dragNodeOriginY + ((event.clientY - dragStartY) / zoomLevel));
        nodeElement.style.left = `${entity.x}px`;
        nodeElement.style.top = `${entity.y}px`;
        renderFlowConnections();
    });

    function stopNodeDragging(event) {
        if (!activeDragNodeId || dragPointerId !== event.pointerId) return;

        const nodeElement = botNodes.querySelector(`.canvas-node[data-node-id="${activeDragNodeId}"]`);
        nodeElement?.classList.remove('dragging');
        if (botNodes.hasPointerCapture(event.pointerId)) {
            botNodes.releasePointerCapture(event.pointerId);
        }
        activeDragNodeId = null;
        dragPointerId = null;
        renderFlowNodes();
    }

    botNodes.addEventListener('pointerup', stopNodeDragging);
    botNodes.addEventListener('pointercancel', stopNodeDragging);

    builderCanvas.addEventListener('wheel', (event) => {
        if (currentStep !== 0 || !isSetupComplete || !event.ctrlKey) return;

        event.preventDefault();
        applyZoom(zoomLevel + (event.deltaY < 0 ? 0.08 : -0.08));
    }, { passive: false });

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
            removeConnectionsForNode(removeButton.dataset.removeNode);
            flowNodes = flowNodes.filter((node) => node.id !== removeButton.dataset.removeNode);
            renderFlowNodes();
            if (activeDrawerTargetId === removeButton.dataset.removeNode || !findFlowEntityById(activeDrawerTargetId)) {
                closeTextMessageDrawer();
            }
            return;
        }

        const openEditorButton = event.target.closest('[data-open-editor]');
        if (openEditorButton) {
            const entity = findFlowEntityById(openEditorButton.dataset.openEditor);
            if (isMessageDrawerNode(entity)) {
                openFlowEditor(entity.id);
            }
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
    syncSessionTimeoutPreview();
    setWabaAccount('', '');
    syncSetupPreview();
    setStep(0);
    applyZoom(1);
    showInteractiveList();
</script>
</body>
</html>
