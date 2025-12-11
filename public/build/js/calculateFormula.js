// Pass formulas from Laravel to JavaScript
// const calculationFormulas = @json($calculationFormulas ?? []);

// Get all test results from the form
function getAllTestResults() {
    const results = {};

    document.querySelectorAll('.test-result').forEach(input => {
        const testId = input.getAttribute('data-test-id');
        const testName = input.closest('tr')
            .querySelector('input[name*="[description]"]')?.value;
        const value = parseFloat(input.value);

        if (testName && !isNaN(value)) {
            // Store with exact test name (preserving special characters and spaces)
            const normalizedName = testName.toUpperCase().trim();
            results[normalizedName] = value;
            results[`TEST_${testId}`] = value; // Also store by ID
        }
    });

    return results;
}

// Parse formula and calculate result
function calculateFormula(formula, testResults) {
    try {
        let expression = formula.toUpperCase().trim();

        // Create a list of test names sorted by length (longest first to avoid partial replacements)
        const testNames = Object.keys(testResults)
            .filter(key => !key.startsWith('TEST_'))
            .sort((a, b) => b.length - a.length);

        console.log('Calculating formula:', formula);
        console.log('Available tests:', testNames);

        // Replace each test name with its value
        for (const testName of testNames) {
            const value = testResults[testName];

            if (value !== null && value !== '' && !isNaN(value)) {
                // Escape special characters for regex (*, #, +, etc.)
                const escapedName = testName.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');

                // Create regex that matches whole test name
                // Use word boundaries or look for operators/spaces around the name
                const regex = new RegExp(escapedName, 'gi');

                // Count occurrences before replacement for debugging
                const matches = expression.match(regex);
                if (matches) {
                    console.log(`Replacing "${testName}" with ${value} (${matches.length} occurrences)`);
                    expression = expression.replace(regex, value);
                }
            }
        }

        console.log('Expression after replacement:', expression);

        // Check if there are still letters remaining (unreplaced test names)
        if (/[A-Z]/i.test(expression)) {
            console.error('Formula still contains unreplaced test names:', expression);
            return null;
        }

        // Clean up the expression (remove extra spaces)
        expression = expression.trim();

        // Evaluate the mathematical expression safely
        const result = new Function('return ' + expression)();

        console.log('Calculation result:', result);

        // Return rounded result or null if invalid
        return (isFinite(result) && !isNaN(result)) ? parseFloat(result.toFixed(2)) : null;

    } catch (error) {
        console.error('Calculation error for formula:', formula);
        console.error('Error details:', error.message);
        return null;
    }
}

    // Add at top-level (after any existing globals)
    let isCalculating = false;
    let pendingRerun = false;
function performCalculations()
{
    // Prevent re-entrant runs; if a run is active, request a rerun and return
    if (isCalculating) {
        pendingRerun = true;
        return;
    }
    isCalculating = true;
    pendingRerun = false;

    const testResults = getAllTestResults();

    console.log('=== Starting Calculations ===');
    console.log('Available test results:', testResults);

    if (Object.keys(testResults).length === 0) {
        console.log('No test results available yet');
        isCalculating = false;
        return;
    }

    // Sort formulas by calculation_order to handle dependencies
    const sortedFormulas = [...calculationFormulas].sort((a, b) =>
        (a.calculation_order || 0) - (b.calculation_order || 0)
    );

    sortedFormulas.forEach((formulaData, index) => {
        const { calculated_test_id, formula, calculated_test_name, calculation_order } = formulaData;

        console.log(`\n--- Calculation ${index + 1} (Order: ${calculation_order}) ---`);
        console.log(`Test: ${calculated_test_name} (ID: ${calculated_test_id})`);
        console.log(`Formula: ${formula}`);

        // Calculate the result
        const calculatedValue = calculateFormula(formula, testResults);

        if (calculatedValue !== null) {
            // Find the input field for this calculated test
            const targetInput = document.querySelector(
                `input.test-result[data-test-id="${calculated_test_id}"]`
            );

            if (targetInput) {
                // Check if it's not manually disabled
                if (!targetInput.hasAttribute('data-manual-entry')) {
                    // Only set value and dispatch if it actually changed (prevents loops)
                    const oldVal = parseFloat(targetInput.value);
                    const needUpdate = isNaN(oldVal) || Math.abs(oldVal - calculatedValue) > 0.000001;

                    if (needUpdate) {
                        targetInput.value = calculatedValue;
                        console.log(`✓ Set ${calculated_test_name} = ${calculatedValue}`);

                        // Update test results for dependent calculations
                        const normalizedName = calculated_test_name.toUpperCase().trim();
                        testResults[normalizedName] = calculatedValue;
                        testResults[`TEST_${calculated_test_id}`] = calculatedValue;

                        // Dispatch a non-bubbling custom event instead of raw 'input' if you prefer,
                        // but dispatching only when value changed avoids loops.
                        targetInput.dispatchEvent(new Event('input', { bubbles: true }));
                    } else {
                        console.log(`= ${calculated_test_name} unchanged (${calculatedValue}), skipping update`);
                    }
                } else {
                    console.log(`⊗ Skipped ${calculated_test_name} - marked for manual entry`);
                }
            } else {
                console.warn(`✗ Could not find input field for test ID: ${calculated_test_id}`);
            }
        } else {
            console.warn(`✗ Could not calculate ${calculated_test_name} - missing required test values`);
        }
    });

    isCalculating = false;

    // If changes happened while calculating, run again once
    if (pendingRerun) {
        pendingRerun = false;
        performCalculations();
    }

    console.log('=== Calculations Complete ===\n');
}

// Debounce function to avoid excessive calculations
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Initialize event listeners
document.addEventListener('DOMContentLoaded', function() {
    console.log('Initializing auto-calculation system');
    console.log('Loaded formulas:', calculationFormulas);

    const debouncedCalculation = debounce(performCalculations, 300);

    // Add event listeners to all test result inputs
    document.querySelectorAll('.test-result').forEach(input => {
        input.addEventListener('input', function() {
            debouncedCalculation();
        });

        input.addEventListener('change', function() {
            performCalculations(); // Immediate calculation on blur/change
        });
    });

    // Mark calculated fields as read-only
    calculationFormulas.forEach(formulaData => {
        const input = document.querySelector(
            `input.test-result[data-test-id="${formulaData.calculated_test_id}"]`
        );

        if (input) {
            // Flag as calculated so other logic can detect it
            input.dataset.calculated = 'true';
            input.setAttribute('title', `Auto-calculated: ${formulaData.formula}`);

            // Add a small icon/badge to indicate it's calculated and allow toggling manual entry
            const badge = document.createElement('span');
            badge.className = 'badge bg-info ms-1';
            badge.style.fontSize = '0.65em';
            badge.style.cursor = 'pointer';
            badge.textContent = 'AUTO';
            badge.title = formulaData.formula;

            // Toggle manual/auto on badge click
            badge.addEventListener('click', (e) => {
                e.stopPropagation();
                const isManual = input.getAttribute('data-manual-entry') === 'true';
                if (isManual) {
                    // Turn auto back on and recalc
                    input.removeAttribute('data-manual-entry');
                    badge.textContent = 'AUTO';
                    badge.className = 'badge bg-info ms-1';
                    performCalculations();
                } else {
                    // Lock as manual so calculations won't overwrite
                    input.setAttribute('data-manual-entry', 'true');
                    badge.textContent = 'MANUAL';
                    badge.className = 'badge bg-warning ms-1';
                }
            });

            // If user types into a calculated field, mark it manual (so it won't be overwritten)
            input.addEventListener('input', () => {
                if (input.dataset.calculated === 'true' && !input.hasAttribute('data-manual-entry')) {
                    input.setAttribute('data-manual-entry', 'true');
                    badge.textContent = 'MANUAL';
                    badge.className = 'badge bg-warning ms-1';
                }
            });

            // Insert badge after the input
            if (input.nextSibling) {
                input.parentNode.insertBefore(badge, input.nextSibling);
            } else {
                input.parentNode.appendChild(badge);
            }
        }
    });

    // Perform initial calculation on page load (if there are existing values)
    setTimeout(() => {
        performCalculations();
    }, 500);

    console.log('Auto-calculation system initialized');
});

// Optional: Add a manual recalculate button
function addRecalculateButton() {
    const button = document.createElement('button');
    button.type = 'button';
    button.className = 'btn btn-sm btn-outline-primary';
    button.innerHTML = '<i class="ri-calculator-line"></i> Recalculate All';
    button.onclick = performCalculations;

    // Add to a suitable location in your form
    const container = document.querySelector('.modal-footer') || document.querySelector('form');
    if (container) {
        container.insertBefore(button, container.firstChild);
    }
}

// Call this if you want a manual recalculate button
// addRecalculateButton();
