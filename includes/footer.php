<?php
declare(strict_types=1);

/**
 * CyLab Academy Design System – Footer
 * Path: includes/footer.php
 */
$include_highlight = $include_highlight ?? false;
?>
    </main><!-- /.cylab-main-viewport -->

    <footer class="cylab-footer">
        <div class="cylab-footer-container">
            <div class="footer-left">
                <span class="footer-brand">ITEC CyberLab</span>
                <span class="footer-dot">•</span>
                <span class="footer-desc">Security Academy &amp; CTF Training Ground</span>
            </div>
            <div class="footer-right">
                <a href="/index.php?page=dashboard" class="footer-link">Dashboard</a>
                <a href="/index.php?page=scoreboard" class="footer-link">Scoreboard</a>
                <a href="https://learn.cylabacademy.org" target="_blank" rel="noopener" class="footer-link">CyLab Academy Design Standard</a>
            </div>
        </div>
    </footer>

    <?php if ($include_highlight): ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
    <script>hljs.highlightAll();</script>
    <?php endif; ?>
    <script src="/assets/app.js"></script>
</body>
</html>
