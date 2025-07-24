document.addEventListener("DOMContentLoaded", function () {
  var x = document.getElementById("order");
  var z = document.getElementById("add");
  var y = document.getElementById("phone");

  x.addEventListener("change", function () {
    if (x.value === "delivery") {
      z.removeAttribute("disabled");
      y.removeAttribute("disabled");
    } else {
      z.setAttribute("disabled", "disabled"); 
      y.setAttribute("disabled", "disabled"); 
    }
  });
});
// --------------- CATEGORY
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.item-hover').forEach(btn => {
    btn.addEventListener('click', function () {
      document.querySelectorAll('.item-hover').forEach(b => b.classList.remove('active'));
      this.classList.add('active');

      const filter = this.getAttribute('data-filter');

      document.querySelectorAll('.product-item').forEach(item => {
        const category = item.getAttribute('data-category');
        if (filter === 'all' || category?.toString() === filter.toString()) {
          item.style.setProperty('display', '', 'important');  // show
        } else {
          item.style.setProperty('display', 'none', 'important'); // hide
        }
      });
    });
  });
});


//----------- Search Box

document.addEventListener('DOMContentLoaded', () => {
  const search = document.getElementById('search');

  function filterItems(containerId, itemSelector, filtertext) {
    const container = document.getElementById(containerId);
    if (!container) {
      console.error(`No container found with id "${containerId}"`);
      return;
    }
    const items = container.querySelectorAll(itemSelector);

    items.forEach(item => {
      const text = item.textContent.toLowerCase();
      if (text.includes(filtertext)) {
        item.style.setProperty('display', '', 'important');
      } else {
        item.style.setProperty('display', 'none', 'important');
      }
    });
  }

  if (!search) {
    console.error('No element with id "search" found');
    return;
  }

  search.addEventListener('input', function () {
    const filter = this.value.toLowerCase();
    filterItems('table', 'tbody tr', filter);
    filterItems('menu', '.product-item', filter);
  });
});

// ------------------Printing recciept and change status to paid

function markPaidAndPrint(orderId, receiptId) {
  const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    fetch('/orders/'+ orderId +'/paid', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token
        },
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            let printContents = document.getElementById(receiptId).innerHTML;
            let originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
            location.reload();
        }
    });
}

// ------------------------- Added another menu to existing customer
document.addEventListener("DOMContentLoaded", () => {
  const allMenuContainers = document.querySelectorAll('[id^="menu-form-container-"]');

  allMenuContainers.forEach(menuContainer => {
    const orderId = menuContainer.id.replace('menu-form-container-', '');
    const menuGroup = menuContainer.querySelector('.menu-group');
    const addButton = menuContainer.querySelector('.add-item');

    // Function to update price input from selected option's data-price
    function updatePrice(selectElem) {
      const selectedOption = selectElem.options[selectElem.selectedIndex];
      const price = selectedOption.getAttribute('data-price') || "0";
      const priceInput = selectElem.closest('.menu-item').querySelector('input[name="price[]"]');
      if (priceInput) {
        priceInput.value = price;
      }
    }

    // Initialize existing selects
    menuGroup.querySelectorAll('select[name="product_id[]"]').forEach(select => {
      updatePrice(select);
    });

    // Change event delegation for selects
    menuGroup.addEventListener('change', e => {
      if (e.target && e.target.matches('select[name="product_id[]"]')) {
        updatePrice(e.target);
      }
    });

    // Add menu item
    if (addButton) {
      addButton.addEventListener('click', () => {
        const firstItem = menuGroup.querySelector('.menu-item');
        if (!firstItem) return;

        const newItem = firstItem.cloneNode(true);

        // Reset inputs
        newItem.querySelectorAll('input, select').forEach(input => {
          if (input.name.includes('quantity')) {
            input.value = 1;
          } else if (input.name.includes('price')) {
            input.value = 0;
          } else if (input.tagName.toLowerCase() === 'select') {
            input.selectedIndex = 0;
          }
        });

        menuGroup.appendChild(newItem);
        updatePrice(newItem.querySelector('select[name="product_id[]"]'));
      });
    }

    // Remove menu item
    menuGroup.addEventListener('click', e => {
      if (e.target.classList.contains('remove-item')) {
        const items = menuGroup.querySelectorAll('.menu-item');
        if (items.length > 1) {
          e.target.closest('.menu-item').remove();
        } else {
          alert('You must have at least one menu item.');
        }
      }
    });
  });
});

// Show/hide menu form for the correct modal
function showneworder(orderId) {
  const formDiv = document.getElementById('menu-form-container-' + orderId);
  if (formDiv) {
    formDiv.classList.toggle('d-none');
  }
}
