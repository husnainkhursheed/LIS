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

// Perform calculations based on formulas
function performCalculations() {
    const testResults = getAllTestResults();

    console.log('=== Starting Calculations ===');
    console.log('Available test results:', testResults);

    if (Object.keys(testResults).length === 0) {
        console.log('No test results available yet');
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
                    // Set the calculated value
                    targetInput.value = calculatedValue;

                    console.log(`✓ Set ${calculated_test_name} = ${calculatedValue}`);

                    // Update test results for dependent calculations
                    const normalizedName = calculated_test_name.toUpperCase().trim();
                    testResults[normalizedName] = calculatedValue;
                    testResults[`TEST_${calculated_test_id}`] = calculatedValue;

                    // Trigger input event to update flags if you have flag calculation logic
                    targetInput.dispatchEvent(new Event('input', { bubbles: true }));
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
            // Style calculated fields
            // input.classList.add('calculated-field');
            // input.style.backgroundColor = '#e8f4fd';
            // input.style.fontWeight = '600';
            // input.setAttribute('readonly', 'readonly');
            // input.setAttribute('title', `Auto-calculated: ${formulaData.formula}`);

            // Add a small icon/badge to indicate it's calculated
            const badge = document.createElement('span');
            badge.className = 'badge bg-info ms-1';
            badge.style.fontSize = '0.65em';
            badge.textContent = 'AUTO';
            badge.title = formulaData.formula;

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
