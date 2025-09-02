/* global fixture, test */
/* eslint-disable new-cap */

import { test, expect } from '@playwright/test';

test("Should display search results", async ({ page }) => {
    await page.goto('http://localhost:8080');
    await page.fill('#search_input', 'Daux');
    await page.click('.Search__icon');

    const countText = await page.textContent('.SearchResults__count');
    expect(countText).toContain('results');

    const titles = await page.$$('.SearchResults__title');
    expect(titles.length).toBeGreaterThan(2);
});
