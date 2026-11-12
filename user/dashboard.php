<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../pages/login.html");
    exit();
}

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

        <!DOCTYPE html>
        <html lang="en">

        <head>

            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">

            <title>LGU Dapa | Resident Dashboard</title>

            <link rel="stylesheet"
                href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

            <style>

                * {
                    margin: 0;
                    padding: 0;
                    box-sizing: border-box;
                }

                html {
                    scroll-behavior: smooth;
                }

                body {
                    font-family: Arial, sans-serif;
                    background: #f4f7fc;
                    color: #172b67;
                    overflow-x: hidden;
                }


                /* =========================================
                PAGE ANIMATION
                ========================================= */

                body {
                    animation: pageFade 0.5s ease;
                }

                @keyframes pageFade {

                    from {
                        opacity: 0;
                    }

                    to {
                        opacity: 1;
                    }

                }


                /* =========================================
                SIDEBAR
                ========================================= */

                .sidebar {
                    position: fixed;
                    left: 0;
                    top: 0;
                    width: 250px;
                    height: 100vh;

                    background: #ffffff;

                    border-right: 1px solid #e3e8f0;

                    padding: 25px 18px;

                    display: flex;
                    flex-direction: column;

                    z-index: 1000;

                    box-shadow:
                        5px 0 25px rgba(15, 23, 42, 0.04);
                }


                /* LOGO */

                .sidebar-logo {
                    display: flex;
                    align-items: center;
                    gap: 11px;

                    padding: 5px 8px 25px;

                    border-bottom: 1px solid #edf0f5;
                }


                .logo-image {
                    width: 48px;
                    height: 48px;

                    border-radius: 13px;

                    border: 1px solid #e1e7ef;

                    background: #ffffff;

                    display: flex;
                    align-items: center;
                    justify-content: center;

                    overflow: hidden;

                    flex-shrink: 0;
                }


                .logo-image img {
                    width: 100%;
                    height: 100%;

                    object-fit: contain;

                    padding: 4px;
                }


                .logo-fallback {
                    color: #1671f9;
                    font-size: 22px;
                }


                .logo-text h2 {
                    color: #172b67;
                    font-size: 17px;
                }


                .logo-text p {
                    color: #64748b;
                    font-size: 9px;

                    margin-top: 3px;

                    line-height: 1.4;
                }


                /* =========================================
                SIDEBAR NAVIGATION
                ========================================= */

                .sidebar-nav {
                    margin-top: 28px;

                    display: flex;
                    flex-direction: column;

                    gap: 7px;
                }


                .nav-link {
                    width: 100%;

                    display: flex;
                    align-items: center;

                    gap: 13px;

                    padding: 12px 13px;

                    border-radius: 11px;

                    text-decoration: none;

                    color: #64748b;

                    font-size: 13px;

                    font-weight: 600;

                    transition: all 0.25s ease;

                    cursor: pointer;
                }


                .nav-link i {
                    width: 20px;

                    text-align: center;

                    font-size: 15px;
                }


                .nav-link:hover {
                    background: #edf4ff;
                    color: #1671f9;

                    transform: translateX(3px);
                }


                .nav-link.active {
                    background:
                        linear-gradient(
                            135deg,
                            #1671f9,
                            #4f8dfc
                        );

                    color: #ffffff;

                    box-shadow:
                        0 8px 20px rgba(22, 113, 249, 0.22);
                }


                .nav-link.active i {
                    color: #ffffff;
                }


                .notification-count {
                    margin-left: auto;

                    width: 21px;
                    height: 21px;

                    background: #1671f9;

                    color: white;

                    border-radius: 50%;

                    display: flex;
                    align-items: center;
                    justify-content: center;

                    font-size: 10px;
                }


                /* =========================================
                SIDEBAR BOTTOM USER
                ========================================= */

                .sidebar-user {
                    margin-top: auto;

                    padding: 15px 10px 5px;

                    border-top: 1px solid #edf0f5;
                }


                .sidebar-user-box {
                    display: flex;
                    align-items: center;

                    gap: 10px;
                }


                .user-avatar {
                    width: 42px;
                    height: 42px;

                    border-radius: 50%;

                    background:
                        linear-gradient(
                            135deg,
                            #dceaff,
                            #edf4ff
                        );

                    color: #1671f9;

                    display: flex;
                    align-items: center;
                    justify-content: center;

                    font-size: 18px;
                }


                .sidebar-user-info {
                    overflow: hidden;
                }


                .sidebar-user-info strong {
                    display: block;

                    color: #172b67;

                    font-size: 12px;

                    white-space: nowrap;

                    overflow: hidden;

                    text-overflow: ellipsis;
                }


                .sidebar-user-info span {
                    display: flex;
                    align-items: center;

                    gap: 5px;

                    margin-top: 4px;

                    color: #64748b;

                    font-size: 10px;
                }


                .online-dot {
                    width: 7px;
                    height: 7px;

                    border-radius: 50%;

                    background: #22c55e;

                    box-shadow:
                        0 0 0 3px rgba(34, 197, 94, 0.12);
                }


                /* =========================================
                MAIN CONTENT
                ========================================= */

                .main {
                    margin-left: 250px;

                    min-height: 100vh;

                    padding: 30px 35px 60px;

                    position: relative;
                }


                /* =========================================
                TOP HEADER
                ========================================= */

                .top-header {
                    display: flex;

                    align-items: center;

                    justify-content: space-between;

                    margin-bottom: 25px;
                }


                .top-title small {
                    color: #1671f9;

                    font-size: 10px;

                    font-weight: bold;

                    letter-spacing: 1.5px;
                }


                .top-title h1 {
                    margin-top: 5px;

                    font-size: 25px;

                    color: #142b70;
                }


                .top-title p {
                    margin-top: 5px;

                    color: #64748b;

                    font-size: 12px;
                }


                .top-actions {
                    display: flex;

                    align-items: center;

                    gap: 10px;
                }


                .notification-button {
                    width: 42px;
                    height: 42px;

                    border: 1px solid #e0e6ef;

                    background: white;

                    border-radius: 11px;

                    color: #64748b;

                    cursor: pointer;

                    position: relative;

                    transition: 0.2s;
                }


                .notification-button:hover {
                    color: #1671f9;

                    transform: translateY(-2px);

                    box-shadow:
                        0 7px 18px rgba(15,23,42,0.08);
                }


                .notification-badge {
                    position: absolute;

                    right: -3px;
                    top: -3px;

                    width: 17px;
                    height: 17px;

                    background: #ef4444;

                    color: white;

                    border-radius: 50%;

                    font-size: 9px;

                    display: flex;
                    align-items: center;
                    justify-content: center;

                    border: 2px solid #f4f7fc;
                }


                .logout-button {
                    border: none;

                    background:
                        linear-gradient(
                            135deg,
                            #1671f9,
                            #3d82fa
                        );

                    color: white;

                    padding: 11px 17px;

                    border-radius: 10px;

                    font-size: 12px;

                    font-weight: bold;

                    cursor: pointer;

                    transition: 0.25s;

                    box-shadow:
                        0 7px 17px rgba(22,113,249,0.18);
                }


                .logout-button:hover {
                    transform: translateY(-2px);

                    box-shadow:
                        0 11px 23px rgba(22,113,249,0.25);
                }


                /* =========================================
                WELCOME HERO
                ========================================= */

                .welcome-card {
                    min-height: 180px;

                    border-radius: 20px;

                    padding: 30px 35px;

                    position: relative;

                    overflow: hidden;

                    background:
                        linear-gradient(
                            135deg,
                            #edf5ff,
                            #ffffff
                        );

                    border: 1px solid #dce9fb;

                    box-shadow:
                        0 10px 30px rgba(15,23,42,0.05);

                    margin-bottom: 25px;
                }


                .welcome-content {
                    position: relative;

                    z-index: 5;

                    max-width: 600px;
                }


                .welcome-label {
                    color: #1671f9;

                    font-size: 10px;

                    font-weight: bold;

                    letter-spacing: 1.5px;
                }


                .welcome-card h2 {
                    margin-top: 8px;

                    color: #142b70;

                    font-size: 31px;
                }


                .welcome-card h2 span {
                    color: #1671f9;
                }


                .welcome-card p {
                    margin-top: 9px;

                    color: #64748b;

                    font-size: 13px;

                    line-height: 1.6;
                }


                .welcome-button {
                    margin-top: 18px;

                    border: none;

                    background: #1671f9;

                    color: white;

                    padding: 10px 16px;

                    border-radius: 9px;

                    font-size: 12px;

                    font-weight: bold;

                    cursor: pointer;

                    transition: 0.25s;
                }


                .welcome-button:hover {
                    transform: translateY(-2px);

                    box-shadow:
                        0 8px 20px rgba(22,113,249,0.25);
                }


                /* DECORATIONS */

                .sun {
                    position: absolute;

                    width: 75px;
                    height: 75px;

                    right: 150px;
                    top: 27px;

                    border-radius: 50%;

                    background:
                        radial-gradient(
                            circle,
                            #ffd75e 0%,
                            #ffb91f 65%,
                            transparent 66%
                        );

                    animation: floatSun 4s ease-in-out infinite;
                }


                @keyframes floatSun {

                    0%,100% {
                        transform: translateY(0);
                    }

                    50% {
                        transform: translateY(-8px);
                    }

                }


                .circle-decoration {
                    position: absolute;

                    width: 230px;
                    height: 230px;

                    right: -70px;
                    top: -80px;

                    border-radius: 50%;

                    border: 35px solid rgba(22,113,249,0.07);
                }


                .cloud {
                    position: absolute;

                    right: 50px;
                    bottom: 25px;

                    width: 140px;
                    height: 35px;

                    background: rgba(22,113,249,0.08);

                    border-radius: 50px;

                    animation: cloudMove 8s ease-in-out infinite;
                }


                .cloud::before,
                .cloud::after {
                    content: "";

                    position: absolute;

                    background: inherit;

                    border-radius: 50%;
                }


                .cloud::before {
                    width: 55px;
                    height: 55px;

                    left: 25px;
                    bottom: 0;
                }


                .cloud::after {
                    width: 42px;
                    height: 42px;

                    right: 25px;
                    bottom: 0;
                }


                @keyframes cloudMove {

                    0%,100% {
                        transform: translateX(0);
                    }

                    50% {
                        transform: translateX(15px);
                    }

                }


                /* =========================================
                STATISTICS
                ========================================= */

                .stats {
                    display: grid;

                    grid-template-columns:
                        repeat(4, 1fr);

                    gap: 15px;

                    margin-bottom: 28px;
                }


                .stat-card {
                    background: white;

                    border: 1px solid #e2e8f0;

                    border-radius: 16px;

                    padding: 18px;

                    display: flex;

                    align-items: center;

                    gap: 13px;

                    position: relative;

                    overflow: hidden;

                    box-shadow:
                        0 6px 20px rgba(15,23,42,0.04);

                    transition:
                        transform 0.25s ease,
                        box-shadow 0.25s ease;
                }


                .stat-card:hover {
                    transform: translateY(-5px);

                    box-shadow:
                        0 13px 30px rgba(15,23,42,0.09);
                }


                .stat-icon {
                    width: 47px;
                    height: 47px;

                    border-radius: 13px;

                    display: flex;

                    align-items: center;

                    justify-content: center;

                    font-size: 18px;

                    flex-shrink: 0;
                }


                .stat-blue {
                    background: #eaf2ff;
                    color: #1671f9;
                }


                .stat-orange {
                    background: #fff3d6;
                    color: #f59e0b;
                }


                .stat-green {
                    background: #dcfce7;
                    color: #16a34a;
                }


                .stat-purple {
                    background: #f0e7ff;
                    color: #7c3aed;
                }


                .stat-card h3 {
                    color: #172b67;

                    font-size: 23px;
                }


                .stat-card p {
                    margin-top: 3px;

                    color: #64748b;

                    font-size: 10px;
                }


                .stat-line {
                    position: absolute;

                    left: 15px;
                    right: 15px;

                    bottom: 0;

                    height: 2px;

                    border-radius: 10px;

                    background:
                        linear-gradient(
                            90deg,
                            transparent,
                            #dce9fb,
                            transparent
                        );

                    animation: lineMove 2.5s ease-in-out infinite;
                }


                @keyframes lineMove {

                    0%,100% {
                        transform: translateX(-20px);
                        opacity: 0.4;
                    }

                    50% {
                        transform: translateX(20px);
                        opacity: 1;
                    }

                }


                /* =========================================
                TWO COLUMN AREA
                ========================================= */

                .content-grid {
                    display: grid;

                    grid-template-columns:
                        minmax(0, 1.65fr)
                        minmax(320px, 1fr);

                    gap: 20px;

                    align-items: start;
                }


                .panel {
                    background: white;

                    border: 1px solid #e1e7ef;

                    border-radius: 17px;

                    padding: 20px;

                    box-shadow:
                        0 6px 20px rgba(15,23,42,0.04);
                }
                .panel-header {
                    display: flex;

                    align-items: center;

                    justify-content: space-between;

                    margin-bottom: 17px;
                }


                .panel-title {
                    display: flex;

                    align-items: center;

                    gap: 9px;
                }


                .panel-title i {
                    color: #1671f9;
                }


                .panel-title h2 {
                    font-size: 17px;

                    color: #172b67;
                }


                .view-all {
                    border: none;

                    background: #edf4ff;

                    color: #1671f9;

                    padding: 7px 11px;

                    border-radius: 8px;

                    font-size: 10px;

                    font-weight: bold;

                    cursor: pointer;

                    transition: 0.2s;
                }


                .view-all:hover {
                    background: #dceaff;
                }


                /* =========================================
                RESOURCE SEARCH
                ========================================= */

                .resource-search {
                    display: flex;

                    align-items: center;

                    gap: 9px;

                    background: #f8fafc;

                    border: 1px solid #e2e8f0;

                    border-radius: 10px;

                    padding: 10px 12px;

                    margin-bottom: 16px;
                }


                .resource-search i {
                    color: #94a3b8;

                    font-size: 13px;
                }


                .resource-search input {
                    width: 100%;

                    border: none;

                    outline: none;

                    background: transparent;

                    color: #334155;

                    font-size: 12px;
                }


                /* =========================================
                RESOURCE GRID
                ========================================= */

                .resource-grid {
                    display: grid;

                    grid-template-columns:
                        repeat(3, 1fr);

                    gap: 12px;
                }


                .resource-card {
                    background: #ffffff;

                    border: 1px solid #e3e8f0;

                    border-radius: 13px;

                    overflow: hidden;

                    transition:
                        transform 0.3s ease,
                        box-shadow 0.3s ease,
                        border-color 0.3s ease;

                    animation: cardAppear 0.5s ease both;
                }


                .resource-card:nth-child(1) {
                    animation-delay: 0.05s;
                }


                .resource-card:nth-child(2) {
                    animation-delay: 0.10s;
                }


                .resource-card:nth-child(3) {
                    animation-delay: 0.15s;
                }


                .resource-card:nth-child(4) {
                    animation-delay: 0.20s;
                }


                .resource-card:nth-child(5) {
                    animation-delay: 0.25s;
                }


                .resource-card:nth-child(6) {
                    animation-delay: 0.30s;
                }


                @keyframes cardAppear {

                    from {
                        opacity: 0;
                        transform: translateY(12px);
                    }

                    to {
                        opacity: 1;
                        transform: translateY(0);
                    }

                }


                .resource-card:hover {
                    transform: translateY(-5px);

                    border-color: #c8dcfb;

                    box-shadow:
                        0 13px 28px rgba(15,23,42,0.10);
                }


                .resource-image {
                    height: 105px;

                    position: relative;

                    display: flex;

                    align-items: center;

                    justify-content: center;

                    overflow: hidden;

                    background:
                        linear-gradient(
                            135deg,
                            #dceaff,
                            #edf4ff
                        );
                }


                .resource-image::before {
                    content: "";

                    position: absolute;

                    width: 130px;
                    height: 130px;

                    border-radius: 50%;

                    background: rgba(255,255,255,0.35);

                    right: -35px;
                    top: -50px;
                }


                .resource-image i {
                    position: relative;

                    z-index: 2;

                    width: 53px;
                    height: 53px;

                    display: flex;

                    align-items: center;

                    justify-content: center;

                    border-radius: 15px;

                    background: rgba(255,255,255,0.9);

                    color: #1671f9;

                    font-size: 22px;

                    box-shadow:
                        0 8px 18px rgba(22,113,249,0.12);

                    transition: 0.3s;
                }


                .resource-card:hover .resource-image i {
                    transform:
                        scale(1.1)
                        rotate(-4deg);
                }


                .resource-content {
                    padding: 12px;
                }


                .resource-content h3 {
                    color: #172b67;

                    font-size: 13px;

                    margin-bottom: 5px;
                }


                .resource-content p {
                    color: #64748b;

                    font-size: 9px;

                    line-height: 1.5;

                    height: 28px;

                    overflow: hidden;
                }


                .resource-type {
                    display: inline-block;

                    margin-top: 8px;

                    padding: 4px 7px;

                    background: #edf4ff;

                    color: #1671f9;

                    border-radius: 20px;

                    font-size: 8px;

                    font-weight: bold;

                    text-transform: uppercase;
                }


                .reserve-button {
                    width: 100%;

                    border: none;

                    margin-top: 10px;

                    background:
                        linear-gradient(
                            135deg,
                            #1671f9,
                            #3c83fa
                        );

                    color: white;

                    padding: 8px;

                    border-radius: 8px;

                    cursor: pointer;

                    font-size: 10px;

                    font-weight: bold;

                    transition: 0.2s;
                }


                .reserve-button:hover {
                    transform: translateY(-2px);

                    box-shadow:
                        0 7px 15px rgba(22,113,249,0.22);
                }


                /* =========================================
                RESERVATIONS
                ========================================= */

                #reservationList {
                    display: flex;

                    flex-direction: column;

                    gap: 9px;
                }


                .reservation-item {
                    display: flex;

                    align-items: center;

                    gap: 10px;

                    padding: 11px;

                    border: 1px solid #e5eaf1;

                    border-radius: 11px;

                    transition: 0.25s;

                    background: #ffffff;
                }


                .reservation-item:hover {
                    transform: translateX(3px);

                    box-shadow:
                        0 6px 15px rgba(15,23,42,0.06);

                    border-color: #d7e5f9;
                }


                .reservation-icon {
                    width: 38px;
                    height: 38px;

                    border-radius: 11px;

                    background: #edf4ff;

                    color: #1671f9;

                    display: flex;

                    align-items: center;

                    justify-content: center;

                    flex-shrink: 0;
                }


                .reservation-info {
                    flex: 1;

                    min-width: 0;
                }


                .reservation-info h3 {
                    color: #172b67;

                    font-size: 11px;

                    margin-bottom: 4px;
                }


                .reservation-meta {
                    display: flex;

                    align-items: center;

                    gap: 7px;

                    color: #64748b;

                    font-size: 8px;

                    white-space: nowrap;
                }


                .reservation-meta i {
                    color: #94a3b8;
                }


                .status {
                    padding: 5px 8px;

                    border-radius: 20px;

                    font-size: 8px;

                    font-weight: bold;

                    white-space: nowrap;
                }


                .status.pending {
                    background: #fff4d6;

                    color: #a16207;
                }


                .status.approved {
                    background: #dcfce7;

                    color: #15803d;
                }


                .status.rejected {
                    background: #fee2e2;

                    color: #dc2626;
                }


                .status.cancelled {
                    background: #e5e7eb;

                    color: #475569;
                }


                .reservation-arrow {
                    color: #cbd5e1;

                    font-size: 10px;
                }


                /* =========================================
                CANCEL BUTTON
                ========================================= */

                .reservation-bottom {
                    margin-top: 7px;

                    display: flex;

                    justify-content: flex-end;
                }


                .cancel-small-btn {
                    border: none;

                    background: #fff1f2;

                    color: #dc2626;

                    padding: 6px 9px;

                    border-radius: 7px;

                    cursor: pointer;

                    font-size: 8px;

                    font-weight: bold;

                    transition: 0.2s;
                }


                .cancel-small-btn:hover {
                    background: #fee2e2;

                    transform: translateY(-1px);
                }


                /* =========================================
                PLAN AHEAD
                ========================================= */

                .plan-card {
                    margin-top: 15px;

                    border-radius: 14px;

                    padding: 17px;

                    background:
                        linear-gradient(
                            135deg,
                            #eaf3ff,
                            #f5f9ff
                        );

                    border: 1px solid #d8e7ff;

                    display: flex;

                    align-items: center;

                    gap: 14px;

                    overflow: hidden;

                    position: relative;
                }


                .plan-icon {
                    width: 55px;
                    height: 55px;

                    border-radius: 13px;

                    background: white;

                    color: #1671f9;

                    display: flex;

                    align-items: center;

                    justify-content: center;

                    font-size: 24px;

                    box-shadow:
                        0 7px 18px rgba(22,113,249,0.10);

                    flex-shrink: 0;

                    animation: calendarFloat 3s ease-in-out infinite;
                }


                @keyframes calendarFloat {

                    0%,100% {
                        transform: translateY(0);
                    }

                    50% {
                        transform: translateY(-4px);
                    }

                }


                .plan-card h3 {
                    color: #172b67;

                    font-size: 13px;

                    margin-bottom: 4px;
                }


                .plan-card p {
                    color: #64748b;

                    font-size: 9px;

                    line-height: 1.5;
                }


                /* =========================================
                EMPTY / LOADING / ERROR
                ========================================= */

                .loading,
                .empty {
                    padding: 35px 15px;

                    text-align: center;

                    color: #64748b;

                    border: 1px dashed #dbe3ee;

                    border-radius: 12px;

                    font-size: 11px;
                }


                .loading i {
                    color: #1671f9;

                    margin-right: 5px;
                }


                .empty i {
                    font-size: 25px;

                    color: #9dbce9;

                    margin-bottom: 10px;
                }


                .empty h3 {
                    color: #172b67;

                    font-size: 13px;

                    margin-bottom: 5px;
                }


                .empty p {
                    font-size: 9px;
                }


                .error {
                    padding: 18px;

                    text-align: center;

                    color: #be123c;

                    background: #fff1f2;

                    border: 1px solid #fecdd3;

                    border-radius: 11px;

                    font-size: 10px;
                }


                /* =========================================
   RESERVATION MODAL - MODERN UI
========================================= */

.modal {
    position: fixed;

    inset: 0;

    background:
        rgba(15, 23, 42, 0.68);

    display: none;

    align-items: center;
    justify-content: center;

    padding: 20px;

    z-index: 3000;

    backdrop-filter: blur(9px);

    -webkit-backdrop-filter: blur(9px);
}


.modal.active {
    display: flex;
}


/* =========================================
   MODAL BOX
========================================= */

.modal-box {
    width: 100%;

    max-width: 470px;

    background:
        linear-gradient(
            145deg,
            #ffffff,
            #f8fbff
        );

    border: 1px solid rgba(255,255,255,0.9);

    border-radius: 26px;

    padding: 30px;

    position: relative;

    overflow: hidden;

    box-shadow:
        0 35px 90px rgba(15, 23, 42, 0.28),
        0 10px 30px rgba(22, 113, 249, 0.08);

    animation:
        reservationModalIn
        0.4s
        cubic-bezier(.16,1,.3,1);
}


/* Decorative glow */

.modal-box::before {

    content: "";

    position: absolute;

    width: 210px;
    height: 210px;

    right: -95px;
    top: -105px;

    border-radius: 50%;

    background:
        radial-gradient(
            circle,
            rgba(22,113,249,0.16),
            rgba(22,113,249,0)
        );

    pointer-events: none;
}


.modal-box::after {

    content: "";

    position: absolute;

    width: 130px;
    height: 130px;

    left: -75px;
    bottom: -70px;

    border-radius: 50%;

    background:
        radial-gradient(
            circle,
            rgba(96,165,250,0.10),
            transparent 70%
        );

    pointer-events: none;
}


@keyframes reservationModalIn {

    from {

        opacity: 0;

        transform:
            translateY(25px)
            scale(0.92);

    }

    to {

        opacity: 1;

        transform:
            translateY(0)
            scale(1);

    }

}


/* =========================================
   MODAL HEADER
========================================= */

.modal-header {

    display: flex;

    align-items: center;

    justify-content: space-between;

    margin-bottom: 23px;

    position: relative;

    z-index: 2;
}


.modal-header h2 {

    color: #172b67;

    font-size: 21px;

    font-weight: 800;

    letter-spacing: -0.3px;

    margin: 0;
}


.modal-header h2::after {

    content: "  •  Reserve a resource";

    color: #94a3b8;

    font-size: 9px;

    font-weight: 500;

    letter-spacing: 0;

}


/* =========================================
   CLOSE BUTTON
========================================= */

.close-modal {

    width: 38px;
    height: 38px;

    border: 1px solid #e2e8f0;

    border-radius: 12px;

    background: #f8fafc;

    color: #64748b;

    cursor: pointer;

    display: flex;

    align-items: center;
    justify-content: center;

    font-size: 14px;

    transition:
        transform 0.25s ease,
        background 0.25s ease,
        color 0.25s ease,
        box-shadow 0.25s ease;

    flex-shrink: 0;
}


.close-modal:hover {

    background: #fff1f2;

    color: #ef4444;

    border-color: #fecdd3;

    transform:
        rotate(90deg)
        scale(1.05);

    box-shadow:
        0 6px 15px rgba(239,68,68,0.10);
}


/* =========================================
   SELECTED RESOURCE
========================================= */

.selected-resource {

    position: relative;

    display: flex;

    align-items: center;

    gap: 12px;

    padding: 14px 15px;

    margin-bottom: 23px;

    background:
        linear-gradient(
            135deg,
            #edf5ff,
            #f6faff
        );

    border: 1px solid #d5e6ff;

    border-radius: 15px;

    color: #1671f9;

    font-size: 12px;

    font-weight: 800;

    box-shadow:
        0 7px 18px rgba(22,113,249,0.06);

    overflow: hidden;

    transition:
        transform 0.25s ease,
        box-shadow 0.25s ease;
}


.selected-resource::before {

    content: "\f1ad";

    font-family:
        "Font Awesome 6 Free";

    font-weight: 900;

    width: 38px;
    height: 38px;

    border-radius: 11px;

    background: #ffffff;

    color: #1671f9;

    display: flex;

    align-items: center;
    justify-content: center;

    font-size: 15px;

    box-shadow:
        0 5px 13px rgba(22,113,249,0.10);

    flex-shrink: 0;
}


.selected-resource::after {

    content: "";

    position: absolute;

    width: 70px;
    height: 70px;

    right: -25px;
    top: -30px;

    border-radius: 50%;

    background:
        rgba(255,255,255,0.55);

    pointer-events: none;
}


.selected-resource:hover {

    transform: translateY(-2px);

    box-shadow:
        0 10px 24px rgba(22,113,249,0.10);
}


/* =========================================
   FORM GROUP
========================================= */

.form-group {

    margin-bottom: 18px;

    position: relative;

    z-index: 2;
}


.form-group label {

    display: flex;

    align-items: center;

    gap: 6px;

    color: #334155;

    font-size: 11px;

    font-weight: 800;

    margin-bottom: 8px;

    letter-spacing: 0.1px;
}


/* =========================================
   INPUT
========================================= */

.form-group input {

    width: 100%;

    height: 48px;

    border: 1px solid #dce4ef;

    border-radius: 13px;

    padding:
        0 14px;

    outline: none;

    background: #ffffff;

    color: #172b67;

    font-size: 12px;

    font-family: Arial, sans-serif;

    transition:
        border-color 0.25s ease,
        box-shadow 0.25s ease,
        background 0.25s ease,
        transform 0.25s ease;
}


.form-group input:hover {

    border-color: #b9d3f8;

    background: #fbfdff;
}


.form-group input:focus {

    border-color: #1671f9;

    background: #ffffff;

    box-shadow:
        0 0 0 4px rgba(22,113,249,0.09),
        0 8px 20px rgba(22,113,249,0.06);

    transform:
        translateY(-1px);
}


/* Date/time browser icons */

.form-group input::-webkit-calendar-picker-indicator {

    cursor: pointer;

    opacity: 0.65;

    transition: 0.2s;
}


.form-group input::-webkit-calendar-picker-indicator:hover {

    opacity: 1;

    transform: scale(1.1);
}


/* =========================================
   FORM MESSAGE
========================================= */

.form-message {

    margin-top: 8px;

    text-align: center;

    color: #64748b;

    font-size: 10px;

    line-height: 1.5;
}


/* =========================================
   SUBMIT BUTTON
========================================= */

.submit-reservation {

    width: 100%;

    min-height: 49px;

    border: none;

    position: relative;

    overflow: hidden;

    background:
        linear-gradient(
            135deg,
            #1671f9,
            #3c83fa
        );

    color: white;

    padding: 13px 16px;

    border-radius: 13px;

    cursor: pointer;

    font-size: 12px;

    font-weight: 800;

    letter-spacing: 0.1px;

    box-shadow:
        0 10px 23px rgba(22,113,249,0.20);

    transition:
        transform 0.25s ease,
        box-shadow 0.25s ease,
        filter 0.25s ease;

    z-index: 2;
}


/* Shine */

.submit-reservation::before {

    content: "";

    position: absolute;

    top: 0;

    left: -100%;

    width: 70%;

    height: 100%;

    background:
        linear-gradient(
            90deg,
            transparent,
            rgba(255,255,255,0.25),
            transparent
        );

    transform: skewX(-20deg);

    transition:
        left 0.6s ease;
}


.submit-reservation:hover::before {

    left: 130%;
}


.submit-reservation:hover {

    transform:
        translateY(-3px);

    box-shadow:
        0 15px 30px rgba(22,113,249,0.28);

    filter:
        brightness(1.04);
}


.submit-reservation:active {

    transform:
        translateY(-1px)
        scale(0.99);

    box-shadow:
        0 7px 16px rgba(22,113,249,0.20);
}


.submit-reservation:disabled {

    opacity: 0.7;

    cursor: not-allowed;

    transform: none;

    box-shadow:
        none;
}


/* =========================================
   MOBILE RESERVATION MODAL
========================================= */

@media (max-width: 600px) {

    .modal {

        padding: 15px;

        align-items: center;

    }


    .modal-box {

        max-width: 100%;

        padding: 24px 20px;

        border-radius: 22px;

        max-height: 92vh;

        overflow-y: auto;

    }


    .modal-header {

        margin-bottom: 20px;

    }


    .modal-header h2 {

        font-size: 18px;

    }


    .modal-header h2::after {

        display: none;

    }


    .close-modal {

        width: 35px;
        height: 35px;

    }


    .selected-resource {

        padding: 12px;

        margin-bottom: 20px;

    }


    .form-group {

        margin-bottom: 15px;

    }


    .form-group input {

        height: 47px;

    }


    .submit-reservation {

        min-height: 48px;

    }

}


/* =========================================
   VERY SMALL PHONES
========================================= */

@media (max-width: 380px) {

    .modal-box {

        padding: 21px 17px;

        border-radius: 20px;

    }


    .modal-header h2 {

        font-size: 17px;

    }


    .selected-resource {

        font-size: 11px;

    }


    .form-group label {

        font-size: 10px;

    }


    .form-group input {

        height: 45px;

        font-size: 11px;

    }

}


/* =========================================
   DARK MODE - RESERVATION MODAL
========================================= */

body.dark-mode .modal-box {

    background:
        linear-gradient(
            145deg,
            #111827,
            #0f172a
        );

    border-color: #1e293b;

    box-shadow:
        0 35px 90px rgba(0,0,0,0.55);

}


body.dark-mode .modal-header h2 {

    color: #f8fafc;

}


body.dark-mode .modal-header h2::after {

    color: #64748b;

}


body.dark-mode .close-modal {

    background: #1e293b;

    border-color: #334155;

    color: #94a3b8;

}


body.dark-mode .close-modal:hover {

    background: #3f1d25;

    border-color: #7f1d1d;

    color: #f87171;

}


body.dark-mode .selected-resource {

    background:
        linear-gradient(
            135deg,
            #172554,
            #1e293b
        );

    border-color: #1e3a8a;

    color: #60a5fa;

}


body.dark-mode .selected-resource::before {

    background: #1e293b;

    color: #60a5fa;

}


body.dark-mode .form-group label {

    color: #cbd5e1;

}


body.dark-mode .form-group input {

    background: #1e293b;

    border-color: #334155;

    color: #f8fafc;

}


body.dark-mode .form-group input:hover {

    border-color: #475569;

    background: #1e293b;

}


body.dark-mode .form-group input:focus {

    border-color: #3b82f6;

    background: #1e293b;

    box-shadow:
        0 0 0 4px rgba(59,130,246,0.12),
        0 8px 20px rgba(0,0,0,0.20);

}


body.dark-mode .form-message {

    color: #94a3b8;

}
                /* =========================================
                CANCEL CONFIRMATION
                ========================================= */

                .cancel-modal {
                    position: fixed;

                    inset: 0;

                    background:
                        rgba(15,23,42,0.65);

                    display: none;

                    align-items: center;

                    justify-content: center;

                    padding: 20px;

                    z-index: 4000;

                    backdrop-filter: blur(5px);
                }


                .cancel-modal.show {
                    display: flex;
                }


                .cancel-modal-box {
                    width: 100%;

                    max-width: 400px;

                    background: white;

                    border-radius: 22px;

                    padding: 30px;

                    text-align: center;

                    box-shadow:
                        0 30px 80px rgba(0,0,0,0.28);

                    animation: cancelPop 0.25s ease;
                }


                @keyframes cancelPop {

                    from {
                        opacity: 0;

                        transform:
                            scale(0.85)
                            translateY(15px);
                    }

                    to {
                        opacity: 1;

                        transform:
                            scale(1)
                            translateY(0);
                    }

                }


                .cancel-icon {
                    width: 67px;
                    height: 67px;

                    margin: 0 auto 15px;

                    border-radius: 50%;

                    background: #fff1f2;

                    color: #dc2626;

                    display: flex;

                    align-items: center;

                    justify-content: center;

                    font-size: 27px;

                    animation: warningPulse 1.5s infinite;
                }


                @keyframes warningPulse {

                    0%,100% {
                        box-shadow:
                            0 0 0 0 rgba(220,38,38,0.10);
                    }

                    50% {
                        box-shadow:
                            0 0 0 10px rgba(220,38,38,0.04);
                    }

                }


                .cancel-modal-box h3 {
                    color: #172b67;

                    font-size: 20px;

                    margin-bottom: 8px;
                }


                .cancel-modal-box p {
                    color: #64748b;

                    font-size: 12px;

                    line-height: 1.6;

                    margin-bottom: 20px;
                }


                .cancel-actions {
                    display: flex;

                    gap: 9px;
                }


                .cancel-no,
                .cancel-yes {
                    flex: 1;

                    padding: 11px;

                    border-radius: 9px;

                    cursor: pointer;

                    font-size: 11px;

                    font-weight: bold;
                }


                .cancel-no {
                    border: 1px solid #dce4ef;

                    background: white;

                    color: #475569;
                }


                .cancel-no:hover {
                    background: #f8fafc;
                }


                .cancel-yes {
                    border: none;

                    background:
                        linear-gradient(
                            135deg,
                            #ef4444,
                            #dc2626
                        );

                    color: white;
                }


                .cancel-yes:hover {
                    box-shadow:
                        0 7px 17px rgba(220,38,38,0.22);
                }


                /* =========================================
                SUCCESS / NOTIFICATION POPUP
                ========================================= */

                .notification-modal {
                    position: fixed;

                    inset: 0;

                    background:
                        rgba(15,23,42,0.62);

                    display: none;

                    align-items: center;

                    justify-content: center;

                    padding: 20px;

                    z-index: 6000;

                    backdrop-filter: blur(5px);
                }


                .notification-modal.show {
                    display: flex;
                }


                .notification-box {
                    width: 100%;

                    max-width: 390px;

                    background: white;

                    border-radius: 22px;

                    padding: 30px;

                    text-align: center;

                    box-shadow:
                        0 30px 80px rgba(0,0,0,0.25);

                    animation: successPop 0.3s ease;
                }


                @keyframes successPop {

                    0% {
                        opacity: 0;

                        transform:
                            scale(0.75)
                            translateY(20px);
                    }

                    70% {
                        transform:
                            scale(1.03);
                    }

                    100% {
                        opacity: 1;

                        transform:
                            scale(1)
                            translateY(0);
                    }

                }


                .notification-icon {
                    width: 72px;
                    height: 72px;

                    margin: 0 auto 16px;

                    border-radius: 50%;

                    display: flex;

                    align-items: center;

                    justify-content: center;

                    font-size: 30px;
                }


                .notification-icon.success {
                    background: #dcfce7;

                    color: #16a34a;

                    animation: successIcon 0.6s ease;
                }


                .notification-icon.error {
                    background: #fee2e2;

                    color: #dc2626;
                }


                @keyframes successIcon {

                    0% {
                        transform: scale(0);
                    }

                    70% {
                        transform: scale(1.15);
                    }

                    100% {
                        transform: scale(1);
                    }

                }


                .notification-box h3 {
                    color: #172b67;

                    font-size: 21px;

                    margin-bottom: 8px;
                }


                .notification-box p {
                    color: #64748b;

                    font-size: 12px;

                    line-height: 1.6;

                    margin-bottom: 20px;
                }


                .notification-btn {
                    width: 100%;

                    border: none;

                    color: white;

                    padding: 12px;

                    border-radius: 10px;

                    cursor: pointer;

                    font-size: 12px;

                    font-weight: bold;
                }


                .notification-btn.success {
                    background:
                        linear-gradient(
                            135deg,
                            #1671f9,
                            #3c83fa
                        );
                }


                .notification-btn.error {
                    background: #dc2626;
                }


                /* =========================================
                MOBILE
                ========================================= */

                @media (max-width: 1100px) {

                    .sidebar {
                        width: 220px;
                    }

                    .main {
                        margin-left: 220px;
                    }

                    .stats {
                        grid-template-columns:
                            repeat(2, 1fr);
                    }

                    .content-grid {
                        grid-template-columns: 1fr;
                    }

                }


                @media (max-width: 800px) {

                    .sidebar {
                        position: relative;

                        width: 100%;

                        height: auto;

                        padding: 15px;

                        border-right: none;

                        border-bottom: 1px solid #e3e8f0;
                    }


                    .sidebar-logo {
                        padding-bottom: 15px;
                    }


                    .sidebar-nav {
                        flex-direction: row;

                        overflow-x: auto;

                        margin-top: 15px;
                    }


                    .nav-link {
                        min-width: 125px;
                    }


                    .sidebar-user {
                        display: none;
                    }


                    .main {
                        margin-left: 0;

                        padding: 22px 15px 50px;
                    }

                }


                @media (max-width: 600px) {

                    .top-header {
                        align-items: flex-start;
                    }


                    .top-title h1 {
                        font-size: 21px;
                    }


                    .logout-button {
                        padding: 9px 11px;
                    }


                    .welcome-card {
                        padding: 25px;
                    }


                    .welcome-card h2 {
                        font-size: 25px;
                    }


                    .sun {
                        right: 30px;
                        top: 20px;
                    }


                    .stats {
                        grid-template-columns: 1fr;
                    }


                    .resource-grid {
                        grid-template-columns: repeat(2, 1fr);
                    }


                    .resource-image {
                        height: 90px;
                    }


                    .reservation-meta {
                        flex-direction: column;

                        align-items: flex-start;

                        gap: 2px;
                    }


                    .reservation-item {
                        align-items: flex-start;
                    }

                }


                @media (max-width: 430px) {

                    .resource-grid {
                        grid-template-columns: 1fr;
                    }


                    .resource-image {
                        height: 110px;
                    }


                    .cancel-actions {
                        flex-direction: column;
                    }

                }

                /* =========================
   THEME TOGGLE BUTTON
========================= */

.theme-toggle {
    width: 42px;
    height: 42px;
    border: 1px solid #dce4ef;
    background: #ffffff;
    color: #172b67;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 17px;
    transition: all 0.3s ease;
}

.theme-toggle:hover {
    background: #edf4ff;
    color: #1671f9;
    transform: translateY(-2px);
}


/* =========================
   DARK MODE
========================= */

body.dark-mode {
    background: #0f172a;
    color: #e2e8f0;
}


/* HEADER */

body.dark-mode .dashboard-header {
    background: #111827;
    border-bottom-color: #1e293b;
}

body.dark-mode .brand h2 {
    color: #f8fafc;
}

body.dark-mode .brand small {
    color: #94a3b8;
}


/* USER BOX */

body.dark-mode .user-box {
    background: #1e293b;
    border-color: #334155;
    color: #e2e8f0;
}


/* THEME BUTTON */

body.dark-mode .theme-toggle {
    background: #1e293b;
    border-color: #334155;
    color: #facc15;
}

body.dark-mode .theme-toggle:hover {
    background: #334155;
    color: #facc15;
}


/* SEARCH */

body.dark-mode .search-box {
    background: #111827;
    border-color: #334155;
}

body.dark-mode .search-box input {
    background: transparent;
    color: #f8fafc;
}

body.dark-mode .search-box input::placeholder {
    color: #64748b;
}


/* CARDS */

body.dark-mode .summary-card,
body.dark-mode .resource-card,
body.dark-mode .reservation-card,
body.dark-mode .loading,
body.dark-mode .empty {
    background: #111827;
    border-color: #1e293b;
    box-shadow: 0 5px 18px rgba(0,0,0,0.2);
}


/* TEXT */

body.dark-mode .summary-card h3,
body.dark-mode .resource-card h3,
body.dark-mode .resource-name,
body.dark-mode .section-heading h2,
body.dark-mode .empty h3 {
    color: #f8fafc;
}

body.dark-mode .summary-card p,
body.dark-mode .resource-card p,
body.dark-mode .section-heading p,
body.dark-mode .empty p,
body.dark-mode .detail label {
    color: #94a3b8;
}

body.dark-mode .detail p {
    color: #e2e8f0;
}


/* RESERVATION */

body.dark-mode .reservation-details {
    border-top-color: #1e293b;
}

body.dark-mode .reservation-actions {
    border-top-color: #1e293b;
}


/* MODALS */

body.dark-mode .modal-box,
body.dark-mode .cancel-modal-box,
body.dark-mode .notification-box {
    background: #111827;
}

body.dark-mode .modal-header h2,
body.dark-mode .cancel-modal-box h3,
body.dark-mode .notification-box h3 {
    color: #f8fafc;
}

body.dark-mode .cancel-modal-box p,
body.dark-mode .notification-box p {
    color: #94a3b8;
}


/* FORM */

body.dark-mode .form-group label {
    color: #cbd5e1;
}

body.dark-mode .form-group input {
    background: #1e293b;
    border-color: #334155;
    color: #f8fafc;
}


/* CLOSE BUTTON */

body.dark-mode .close-modal,
body.dark-mode .cancel-no-btn {
    background: #1e293b;
    border-color: #334155;
    color: #cbd5e1;
}


            </style>

        </head>


        <body>


            <!-- =========================================
                SIDEBAR
            ========================================= -->

            <aside class="sidebar">


                <div class="sidebar-logo">


                    <div class="logo-image">

                        <img
                            src="picture/images.jpg"
                            alt="LGU Dapa Logo"
                            onerror="this.style.display='none'; document.getElementById('logoFallback').style.display='block';">

                        <i
                            id="logoFallback"
                            class="fa-solid fa-building-columns logo-fallback"
                            style="display:none;">
                        </i>

                    </div>


                    <div class="logo-text">

                        <h2>LGU DAPA</h2>

                        <p>
                            Facilities & Equipment<br>
                            Reservation
                        </p>

                    </div>

                </div>



                <nav class="sidebar-nav">


                    <a
                        class="nav-link active"
                        href="#top">

                        <i class="fa-solid fa-house"></i>

                        Dashboard

                    </a>


                    <a
                        class="nav-link"
                        href="#resources">

                        <i class="fa-solid fa-building"></i>

                        Browse Resources

                    </a>


                    <a
                        class="nav-link"
                        href="#myReservations">

                        <i class="fa-solid fa-calendar-check"></i>

                        My Reservations

                    </a>

                    <a
                        class="nav-link"
                        href="profile.php">

                        <i class="fa-solid fa-user"></i>

                        Profile

                    </a>


                

                </nav>



                <!-- SIDEBAR USER -->

                <div class="sidebar-user">

                    <div class="sidebar-user-box">


                        <div class="user-avatar">

                            <i class="fa-solid fa-user"></i>

                        </div>


                        <div class="sidebar-user-info">

                            <strong id="sidebarUserName">
                                User
                            </strong>

                            <span>

                                <span class="online-dot"></span>

                                Online

                            </span>

                        </div>


                    </div>

                </div>


            </aside>



            <!-- =========================================
                MAIN
            ========================================= -->

            <main class="main" id="top">


                <!-- TOP HEADER -->

                <div class="top-header">


                    <div class="top-title">

                        <small>
                            RESIDENT DASHBOARD
                        </small>

                        <h1>
                            Welcome back,
                            <span id="topUserName">
                                User
                            </span>!
                        </h1>

                        <p>
                            Manage your reservations and explore LGU Dapa facilities & equipment.
                        </p>

                    </div>


                    <div class="top-actions">


                       <button
                           type="button"
                           class="theme-toggle"
                           id="themeToggle"
                           title="Dark Mode">

                         <i class="fa-solid fa-moon" id="themeIcon"></i>

                        </button>

                        <button
                            class="logout-button"
                            onclick="logout()">

                            <i class="fa-solid fa-right-from-bracket"></i>

                            Logout

                        </button>


                    </div>

                </div>



                <!-- =========================================
                    WELCOME HERO
                ========================================= -->

                <section class="welcome-card">


                    


                        

                        <h2>

                            
                            <span id="welcomeName">
                                User
                            </span>!

                        </h2>


                      


                        <button
                            class="welcome-button"
                            onclick="scrollToResources()">

                            <i class="fa-solid fa-compass"></i>

                            Explore Resources

                        </button>


                    </div>


                    <div class="sun"></div>

                    <div class="cloud"></div>

                    <div class="circle-decoration"></div>


                </section>



                <!-- =========================================
                    STATISTICS
                ========================================= -->

                <section class="stats">


                    <div class="stat-card">


                        <div class="stat-icon stat-blue">

                            <i class="fa-solid fa-calendar-check"></i>

                        </div>


                        <div>

                            <h3 id="totalCount">
                                0
                            </h3>

                            <p>
                                Total Reservations
                            </p>

                        </div>


                        <div class="stat-line"></div>


                    </div>



                    <div class="stat-card">


                        <div class="stat-icon stat-orange">

                            <i class="fa-solid fa-clock"></i>

                        </div>


                        <div>

                            <h3 id="pendingCount">
                                0
                            </h3>

                            <p>
                                Pending Requests
                            </p>

                        </div>


                        <div class="stat-line"></div>


                    </div>



                    <div class="stat-card">


                        <div class="stat-icon stat-green">

                            <i class="fa-solid fa-circle-check"></i>

                        </div>


                        <div>

                            <h3 id="approvedCount">
                                0
                            </h3>

                            <p>
                                Approved Reservations
                            </p>

                        </div>


                        <div class="stat-line"></div>


                    </div>



                    <div class="stat-card">


                        <div class="stat-icon stat-purple">

                            <i class="fa-solid fa-ban"></i>

                        </div>


                        <div>

                            <h3 id="cancelledCount">
                                0
                            </h3>

                            <p>
                                Cancelled Reservations
                            </p>

                        </div>


                        <div class="stat-line"></div>


                    </div>


                </section>



                <!-- =========================================
                    CONTENT GRID
                ========================================= -->

                <div class="content-grid">



                    <!-- =====================================
                        RESOURCES
                    ====================================== -->

                    <section
                        class="panel"
                        id="resources">


                        <div class="panel-header">


                            <div class="panel-title">

                                <i class="fa-solid fa-building"></i>

                                <h2>
                                    Browse Facilities & Equipment
                                </h2>

                            </div>


                            <button
                                class="view-all"
                                onclick="document.getElementById('resourceSearch').focus()">

                                View All

                            </button>


                        </div>



                        <!-- SEARCH -->

                        <div class="resource-search">

                            <i class="fa-solid fa-magnifying-glass"></i>

                            <input
                                type="text"
                                id="resourceSearch"
                                placeholder="Search facility or equipment..."
                                oninput="searchResources()">

                        </div>



                        <!-- RESOURCE GRID -->

                        <div
                            class="resource-grid"
                            id="resourceGrid">



                            <!-- GYMNASIUM -->

                            <div
                                class="resource-card"
                                data-name="Dapa Gymnasium"
                                data-type="facility">


                                <div class="resource-image">

                                    <i class="fa-solid fa-dumbbell"></i>

                                </div>


                                <div class="resource-content">

                                    <h3>
                                        Dapa Gymnasium
                                    </h3>

                                    <p>
                                        Spacious facility for sports,
                                        programs, events and activities.
                                    </p>

                                    <span class="resource-type">
                                        Facility
                                    </span>


                                    <button
                                        class="reserve-button"
                                        onclick="openReservation('Dapa Gymnasium')">

                                        <i class="fa-solid fa-calendar-plus"></i>

                                        Reserve Now

                                    </button>

                                </div>

                            </div>



                            <!-- TEATRO -->

                            <div
                                class="resource-card"
                                data-name="Teatro"
                                data-type="facility">


                                <div class="resource-image">

                                    <i class="fa-solid fa-masks-theater"></i>

                                </div>


                                <div class="resource-content">

                                    <h3>
                                        Teatro
                                    </h3>

                                    <p>
                                        Community theater for programs,
                                        presentations and special events.
                                    </p>

                                    <span class="resource-type">
                                        Facility
                                    </span>


                                    <button
                                        class="reserve-button"
                                        onclick="openReservation('Teatro')">

                                        <i class="fa-solid fa-calendar-plus"></i>

                                        Reserve Now

                                    </button>

                                </div>

                            </div>



                            <!-- COVERED COURT -->

                            <div
                                class="resource-card"
                                data-name="Covered Court"
                                data-type="facility">


                                <div class="resource-image">

                                    <i class="fa-solid fa-basketball"></i>

                                </div>


                                <div class="resource-content">

                                    <h3>
                                        Covered Court
                                    </h3>

                                    <p>
                                        Community court for sports,
                                        activities and gatherings.
                                    </p>

                                    <span class="resource-type">
                                        Facility
                                    </span>


                                    <button
                                        class="reserve-button"
                                        onclick="openReservation('Covered Court')">

                                        <i class="fa-solid fa-calendar-plus"></i>

                                        Reserve Now

                                    </button>

                                </div>

                            </div>



                            <!-- CHAIRS -->

                            <div
                                class="resource-card"
                                data-name="Chairs"
                                data-type="equipment">


                                <div class="resource-image">

                                    <i class="fa-solid fa-chair"></i>

                                </div>


                                <div class="resource-content">

                                    <h3>
                                        Chairs
                                    </h3>

                                    <p>
                                        LGU chairs for community events,
                                        meetings and programs.
                                    </p>

                                    <span class="resource-type">
                                        Equipment
                                    </span>


                                    <button
                                        class="reserve-button"
                                        onclick="openReservation('Chairs')">

                                        <i class="fa-solid fa-calendar-plus"></i>

                                        Reserve Now

                                    </button>

                                </div>

                            </div>



                            <!-- TABLES -->

                            <div
                                class="resource-card"
                                data-name="Tables"
                                data-type="equipment">


                                <div class="resource-image">

                                    <i class="fa-solid fa-table"></i>

                                </div>


                                <div class="resource-content">

                                    <h3>
                                        Tables
                                    </h3>

                                    <p>
                                        Tables for meetings, programs
                                        and community activities.
                                    </p>

                                    <span class="resource-type">
                                        Equipment
                                    </span>


                                    <button
                                        class="reserve-button"
                                        onclick="openReservation('Tables')">

                                        <i class="fa-solid fa-calendar-plus"></i>

                                        Reserve Now

                                    </button>

                                </div>

                            </div>



                            <!-- TENTS -->

                            <div
                                class="resource-card"
                                data-name="Tents"
                                data-type="equipment">


                                <div class="resource-image">

                                    <i class="fa-solid fa-tent"></i>

                                </div>


                                <div class="resource-content">

                                    <h3>
                                        Tents
                                    </h3>

                                    <p>
                                        Event tents for outdoor programs
                                        and community activities.
                                    </p>

                                    <span class="resource-type">
                                        Equipment
                                    </span>


                                    <button
                                        class="reserve-button"
                                        onclick="openReservation('Tents')">

                                        <i class="fa-solid fa-calendar-plus"></i>

                                        Reserve Now

                                    </button>

                                </div>

                            </div>


                        </div>


                    </section>



                    <!-- =====================================
                        MY RESERVATIONS
                    ====================================== -->

                    <section
                        class="panel"
                        id="myReservations">


                        <div class="panel-header">


                            <div class="panel-title">

                                <i class="fa-solid fa-calendar-check"></i>

                                <h2>
                                    My Reservations
                                </h2>

                            </div>


                            <button
                                class="view-all"
                                onclick="loadReservations()">

                                Refresh

                            </button>


                        </div>


                        <div id="reservationList">


                            <div class="loading">

                                <i class="fa-solid fa-spinner fa-spin"></i>

                                Loading reservations...

                            </div>


                        </div>



                        <!-- PLAN AHEAD -->

                        <div class="plan-card">


                            <div class="plan-icon">

                                <i class="fa-regular fa-calendar"></i>

                            </div>


                            <div>

                                <h3>
                                    Plan ahead!
                                </h3>

                                <p>
                                    Make your reservation early
                                    to secure your preferred schedule.
                                </p>

                            </div>


                        </div>


                    </section>


                </div>


            </main>



            <!-- =========================================
                RESERVATION MODAL
            ========================================= -->

            <div
                class="modal"
                id="reservationModal">


                <div class="modal-box">


                    <div class="modal-header">

                        <h2>
                            Make a Reservation
                        </h2>


                        <button
                            type="button"
                            class="close-modal"
                            onclick="closeReservation()">

                            <i class="fa-solid fa-xmark"></i>

                        </button>

                    </div>


                    <div class="selected-resource">

                        <i class="fa-solid fa-building"></i>

                        <span id="selectedResource">
                            Resource
                        </span>

                    </div>


                    <form id="reservationForm">


                        <input
                            type="hidden"
                            id="reservationItem"
                            name="item">


                        <div class="form-group">

                            <label>
                                Reservation Date
                            </label>

                            <input
                                type="date"
                                id="reservationDate"
                                name="date"
                                required>

                        </div>


                        <div class="form-group">

                            <label>
                                Reservation Time
                            </label>

                            <input
                                type="time"
                                id="reservationTime"
                                name="time"
                                required>

                        </div>


                        <button
                            type="submit"
                            class="submit-reservation"
                            id="submitReservation">

                            <i class="fa-solid fa-paper-plane"></i>

                            Submit Reservation

                        </button>


                        <div
                            class="form-message"
                            id="formMessage">
                        </div>


                    </form>


                </div>

            </div>



            <!-- =========================================
                CANCEL CONFIRMATION MODAL
            ========================================= -->

            <div
                class="cancel-modal"
                id="cancelConfirmModal">


                <div class="cancel-modal-box">


                    <div class="cancel-icon">

                        <i class="fa-solid fa-triangle-exclamation"></i>

                    </div>


                    <h3>
                        Cancel Reservation?
                    </h3>


                    <p>
                        Are you sure you want to cancel this reservation?
                        Please confirm before continuing.
                    </p>


                    <div class="cancel-actions">


                        <button
                            type="button"
                            class="cancel-no"
                            onclick="closeCancelModal()">

                            Keep Reservation

                        </button>


                        <button
                            type="button"
                            class="cancel-yes"
                            onclick="confirmDashboardCancel()">

                            <i class="fa-solid fa-trash"></i>

                            Yes, Cancel

                        </button>


                    </div>


                </div>


            </div>



            <!-- =========================================
                CUSTOM NOTIFICATION
            ========================================= -->

            <div
                class="notification-modal"
                id="notificationModal">


                <div class="notification-box">


                    <div
                        class="notification-icon success"
                        id="notificationIcon">

                        <i
                            class="fa-solid fa-check"
                            id="notificationIconSymbol">
                        </i>

                    </div>


                    <h3 id="notificationTitle">
                        Success!
                    </h3>


                    <p id="notificationMessage">
                        Your action was completed successfully.
                    </p>


                    <button
                        type="button"
                        class="notification-btn success"
                        id="notificationButton"
                        onclick="closeNotification()">

                        Done

                    </button>


                </div>


            </div>



            <!-- =========================================
                JAVASCRIPT
            ========================================= -->

            <script>


                /* =========================================
                USER INFORMATION
                ========================================= */

                const savedName =
                    localStorage.getItem("lguUserName");


                if (savedName) {

                    document.getElementById(
                        "sidebarUserName"
                    ).textContent = savedName;


                    document.getElementById(
                        "topUserName"
                    ).textContent = savedName;


                    document.getElementById(
                        "welcomeName"
                    ).textContent = savedName;

                }



                /* =========================================
                SCROLL FUNCTIONS
                ========================================= */

                function scrollToResources() {

                    document
                        .getElementById("resources")
                        .scrollIntoView({
                            behavior: "smooth"
                        });

                }


                function scrollToReservations() {

                    document
                        .getElementById("myReservations")
                        .scrollIntoView({
                            behavior: "smooth"
                        });

                }



                /* =========================================
                RESOURCE SEARCH
                ========================================= */

                function searchResources() {

                    const search =
                        document
                            .getElementById("resourceSearch")
                            .value
                            .toLowerCase()
                            .trim();


                    const cards =
                        document.querySelectorAll(
                            ".resource-card"
                        );


                    cards.forEach(card => {

                        const name =
                            card.dataset.name
                                .toLowerCase();


                        const type =
                            card.dataset.type
                                .toLowerCase();


                        if (
                            name.includes(search) ||
                            type.includes(search)
                        ) {

                            card.style.display = "";

                        } else {

                            card.style.display = "none";

                        }

                    });

                }



                /* =========================================
                OPEN RESERVATION
                ========================================= */

                function openReservation(resource) {

                    document
                        .getElementById("reservationModal")
                        .classList.add("active");


                    document
                        .getElementById("selectedResource")
                        .textContent = resource;


                    document
                        .getElementById("reservationItem")
                        .value = resource;


                    document
                        .getElementById("reservationDate")
                        .value = "";


                    document
                        .getElementById("reservationTime")
                        .value = "";


                    document
                        .getElementById("formMessage")
                        .textContent = "";

                }



                /* =========================================
                CLOSE RESERVATION
                ========================================= */

                function closeReservation() {

                    document
                        .getElementById("reservationModal")
                        .classList.remove("active");

                }



                document
                    .getElementById("reservationModal")
                    .addEventListener(
                        "click",
                        function(event) {

                            if (event.target === this) {

                                closeReservation();

                            }

                        }
                    );



                /* =========================================
                SUBMIT RESERVATION
                ========================================= */

                document
                    .getElementById("reservationForm")
                    .addEventListener(
                        "submit",
                        async function(event) {

                            event.preventDefault();


                            const button =
                                document.getElementById(
                                    "submitReservation"
                                );


                            const formData =
                                new FormData(this);


                            button.disabled = true;


                            button.innerHTML =
                                '<i class="fa-solid fa-spinner fa-spin"></i> Submitting...';


                            try {

                                const response =
                                    await fetch(
                                        "../reserve.php",
                                        {
                                            method: "POST",

                                            body: formData,

                                            credentials:
                                                "same-origin"
                                        }
                                    );


                                const data =
                                    await response.json();


                                if (data.success) {


                                    closeReservation();


                                    this.reset();


                                    loadReservations();


                                    showNotification(
                                        "Reservation Submitted!",
                                        "Your reservation has been successfully submitted and is now waiting for approval.",
                                        "success"
                                    );


                                } else {


                                    showNotification(
                                        "Reservation Failed",
                                        data.message ||
                                        "Unable to submit your reservation.",
                                        "error"
                                    );

                                }


                            }

                            catch (error) {

                                console.error(
                                    "Reservation Error:",
                                    error
                                );


                                showNotification(
                                    "Something Went Wrong",
                                    "Unable to connect to the reservation server. Please try again.",
                                    "error"
                                );

                            }


                            button.disabled = false;


                            button.innerHTML =
                                '<i class="fa-solid fa-paper-plane"></i> Submit Reservation';

                        }
                    );



                /* =========================================
                LOAD RESERVATIONS
                
                IMPORTANT:
                Correct PHP filename is:
                my_reservation.php
                ========================================= */

                async function loadReservations() {

                    const reservationList =
                        document.getElementById(
                            "reservationList"
                        );


                    reservationList.innerHTML =

                        `<div class="loading">

                            <i class="fa-solid fa-spinner fa-spin"></i>

                            Loading reservations...

                        </div>`;


                    try {


                        const response =
                            await fetch(
                                "../my_reservations.php",
                                {
                                    credentials:
                                        "same-origin",

                                    cache: "no-store"
                                }
                            );


                        const data =
                            await response.json();


                        if (!data.success) {


                            reservationList.innerHTML =

                                `<div class="error">

                                    <i class="fa-solid fa-circle-exclamation"></i>

                                    ${data.message ||
                                    "Unable to load your reservations."}

                                </div>`;


                            return;

                        }


                        const reservations =
                            data.reservations || [];


                        /* TOTAL */

                        document
                            .getElementById(
                                "totalCount"
                            )
                            .textContent =
                            reservations.length;


                        /* PENDING */

                        const pending =
                            reservations.filter(
                                reservation =>

                                    String(
                                        reservation.status
                                    )
                                    .toLowerCase()
                                    === "pending"

                            ).length;


                        document
                            .getElementById(
                                "pendingCount"
                            )
                            .textContent =
                            pending;


                        /* APPROVED */

                        const approved =
                            reservations.filter(
                                reservation =>

                                    String(
                                        reservation.status
                                    )
                                    .toLowerCase()
                                    === "approved"

                            ).length;


                        document
                            .getElementById(
                                "approvedCount"
                            )
                            .textContent =
                            approved;


                        /* CANCELLED */

                        const cancelled =
                            reservations.filter(
                                reservation => {

                                    const status =
                                        String(
                                            reservation.status
                                        )
                                        .toLowerCase();

                                    return (
                                        status ===
                                        "cancelled" ||

                                        status ===
                                        "canceled"
                                    );

                                }
                            ).length;


                        document
                            .getElementById(
                                "cancelledCount"
                            )
                            .textContent =
                            cancelled;



                        /* NO RESERVATIONS */

                        if (
                            reservations.length === 0
                        ) {


                            reservationList.innerHTML =

                                `<div class="empty">

                                    <i class="fa-regular fa-calendar"></i>

                                    <h3>
                                        No Reservations Yet
                                    </h3>

                                    <p>
                                        Browse the available resources
                                        and make your first reservation.
                                    </p>

                                </div>`;


                            return;

                        }



                        /* RESERVATION CARDS */

                        reservationList.innerHTML =

                            reservations
                                .map(
                                    reservation => {


                                        const status =
                                            String(
                                                reservation.status
                                            )
                                            .toLowerCase();


                                        let statusClass =
                                            "pending";


                                        let statusIcon =
                                            "fa-clock";


                                        if (
                                            status ===
                                            "approved"
                                        ) {

                                            statusClass =
                                                "approved";

                                            statusIcon =
                                                "fa-circle-check";

                                        }

                                        else if (
                                            status ===
                                            "rejected"
                                        ) {

                                            statusClass =
                                                "rejected";

                                            statusIcon =
                                                "fa-circle-xmark";

                                        }

                                        else if (
                                            status ===
                                            "cancelled" ||

                                            status ===
                                            "canceled"
                                        ) {

                                            statusClass =
                                                "cancelled";

                                            statusIcon =
                                                "fa-ban";

                                        }


                                        let cancelButton = "";


                                        if (
                                            status ===
                                            "pending"
                                        ) {

                                            cancelButton =

                                                `<div class="reservation-bottom">

                                                    <button
                                                        type="button"
                                                        class="cancel-small-btn"
                                                        onclick="cancelReservation(${reservation.id})">

                                                        <i class="fa-solid fa-xmark"></i>

                                                        Cancel Reservation

                                                    </button>

                                                </div>`;

                                        }



                                        return `

                                            <div class="reservation-item">

                                                <div class="reservation-icon">

                                                    <i class="fa-solid fa-calendar-check"></i>

                                                </div>


                                                <div class="reservation-info">

                                                    <h3>
                                                        ${reservation.resource_name}
                                                    </h3>

                                                    <div class="reservation-meta">

                                                        <span>

                                                            <i class="fa-regular fa-calendar"></i>

                                                            ${reservation.reservation_date}

                                                        </span>


                                                        <span>

                                                            <i class="fa-regular fa-clock"></i>

                                                            ${reservation.reservation_time}

                                                        </span>

                                                    </div>


                                                    ${cancelButton}

                                                </div>


                                                <span
                                                    class="status ${statusClass}">

                                                    <i
                                                        class="fa-solid ${statusIcon}">
                                                    </i>

                                                    ${reservation.status}

                                                </span>


                                                <i
                                                    class="fa-solid fa-chevron-right reservation-arrow">
                                                </i>

                                            </div>

                                        `;

                                    }
                                )
                                .join("");


                    }

                    catch (error) {


                        console.error(
                            "Reservation loading error:",
                            error
                        );


                        reservationList.innerHTML =

                            `<div class="error">

                                <i class="fa-solid fa-circle-exclamation"></i>

                                Unable to load your reservations.
                                Please try again.

                            </div>`;

                    }

                }



                /* =========================================
                CANCEL RESERVATION
                ========================================= */

                let dashboardCancelId = null;


                function cancelReservation(id) {

                    dashboardCancelId = id;


                    document
                        .getElementById(
                            "cancelConfirmModal"
                        )
                        .classList.add("show");

                }



                /* =========================================
                CLOSE CANCEL MODAL
                ========================================= */

                function closeCancelModal() {

                    dashboardCancelId = null;


                    document
                        .getElementById(
                            "cancelConfirmModal"
                        )
                        .classList.remove("show");

                }



                /* =========================================
                CONFIRM CANCEL
                ========================================= */

                async function confirmDashboardCancel() {


                    if (!dashboardCancelId) {

                        return;

                    }


                    const id =
                        dashboardCancelId;


                    closeCancelModal();


                    try {


                        const response =
                            await fetch(
                                "../cancel_reservation.php",
                                {
                                    method: "POST",

                                    headers: {
                                        "Content-Type":
                                            "application/x-www-form-urlencoded",

                                        "Accept":
                                            "application/json"
                                    },

                                    body:
                                        new URLSearchParams({
                                            id: id
                                        }),

                                    credentials:
                                        "same-origin"
                                }
                            );


                        const data =
                            await response.json();


                        if (data.success) {


                            showNotification(
                                "Reservation Cancelled",
                                data.message ||
                                "Your reservation has been cancelled successfully.",
                                "success"
                            );


                            loadReservations();


                        } else {


                            showNotification(
                                "Cancellation Failed",
                                data.message ||
                                "Unable to cancel this reservation.",
                                "error"
                            );

                        }


                    }

                    catch (error) {


                        console.error(
                            "Cancellation Error:",
                            error
                        );


                        showNotification(
                            "Something Went Wrong",
                            "Could not connect to the reservation server.",
                            "error"
                        );

                    }

                }



                /* =========================================
                CLOSE CANCEL MODAL OUTSIDE CLICK
                ========================================= */

                document
                    .getElementById(
                        "cancelConfirmModal"
                    )
                    .addEventListener(
                        "click",
                        function(event) {

                            if (
                                event.target === this
                            ) {

                                closeCancelModal();

                            }

                        }
                    );



                /* =========================================
                CUSTOM NOTIFICATION
                ========================================= */

                function showNotification(
                    title,
                    message,
                    type = "success"
                ) {


                    const modal =
                        document.getElementById(
                            "notificationModal"
                        );


                    const titleElement =
                        document.getElementById(
                            "notificationTitle"
                        );


                    const messageElement =
                        document.getElementById(
                            "notificationMessage"
                        );


                    const icon =
                        document.getElementById(
                            "notificationIcon"
                        );


                    const iconSymbol =
                        document.getElementById(
                            "notificationIconSymbol"
                        );


                    const button =
                        document.getElementById(
                            "notificationButton"
                        );


                    titleElement.textContent =
                        title;


                    messageElement.textContent =
                        message;


                    if (
                        type === "success"
                    ) {


                        icon.className =
                            "notification-icon success";


                        iconSymbol.className =
                            "fa-solid fa-check";


                        button.className =
                            "notification-btn success";


                    } else {


                        icon.className =
                            "notification-icon error";


                        iconSymbol.className =
                            "fa-solid fa-xmark";


                        button.className =
                            "notification-btn error";

                    }


                    modal.classList.add("show");

                }



                /* =========================================
                CLOSE NOTIFICATION
                ========================================= */

                function closeNotification() {

                    document
                        .getElementById(
                            "notificationModal"
                        )
                        .classList.remove("show");

                }



                /* =========================================
                CLOSE NOTIFICATION OUTSIDE
                ========================================= */

                document
                    .getElementById(
                        "notificationModal"
                    )
                    .addEventListener(
                        "click",
                        function(event) {

                            if (
                                event.target === this
                            ) {

                                closeNotification();

                            }

                        }
                    );



                /* =========================================
                LOGOUT
                ========================================= */

                function logout() {


                    localStorage.removeItem(
                        "lguLoggedIn"
                    );


                    localStorage.removeItem(
                        "lguUserName"
                    );


                    localStorage.removeItem(
                        "lguUserEmail"
                    );


                    localStorage.removeItem(
                        "lguUserRole"
                    );


                    window.location.replace(
                        "../auth/logout.php"
                    );

                }



                /* =========================================
                START
                ========================================= */
                /* =========================================
DARK / LIGHT MODE
========================================= */

const themeToggle =
    document.getElementById("themeToggle");

const themeIcon =
    document.getElementById("themeIcon");


/* LOAD SAVED THEME */

const savedTheme =
    localStorage.getItem("lguTheme");


if (savedTheme === "dark") {

    document.body.classList.add("dark-mode");

    themeIcon.classList.remove("fa-moon");

    themeIcon.classList.add("fa-sun");

    themeToggle.setAttribute(
        "title",
        "Switch to Light Mode"
    );

}


/* TOGGLE THEME */

themeToggle.addEventListener(
    "click",
    function () {

        document.body.classList.toggle(
            "dark-mode"
        );


        if (
            document.body.classList.contains(
                "dark-mode"
            )
        ) {

            /* SAVE DARK MODE */

            localStorage.setItem(
                "lguTheme",
                "dark"
            );


            /* CHANGE MOON TO SUN */

            themeIcon.classList.remove(
                "fa-moon"
            );

            themeIcon.classList.add(
                "fa-sun"
            );


            themeToggle.setAttribute(
                "title",
                "Switch to Light Mode"
            );


        } else {

            /* SAVE LIGHT MODE */

            localStorage.setItem(
                "lguTheme",
                "light"
            );


            /* CHANGE SUN TO MOON */

            themeIcon.classList.remove(
                "fa-sun"
            );

            themeIcon.classList.add(
                "fa-moon"
            );


            themeToggle.setAttribute(
                "title",
                "Switch to Dark Mode"
            );

        }

    }
);

                loadReservations();


            </script>


        </body>

        </html>