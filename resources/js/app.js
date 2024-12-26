
import './bootstrap'
import jQuery from 'jquery'
window.$ = jQuery
import axios from 'axios'
import Swal from 'sweetalert2/dist/sweetalert2.js'
import 'sweetalert2/src/sweetalert2.scss'
window.Swal = Swal // Make Swal available globally

// Import Chart.js
import { Chart } from 'chart.js'

// Import flatpickr
import flatpickr from 'flatpickr'

// import component from './components/component';
//For Dark and Light Mode
import dashboardCard04 from './components/dashboard-card-04'

import Alpine from 'alpinejs'
window.Alpine = Alpine
Alpine.start()

// Define Chart.js default settings
/* eslint-disable prefer-destructuring */
Chart.defaults.font.family = '"Inter", sans-serif'
Chart.defaults.font.weight = 500
Chart.defaults.plugins.tooltip.borderWidth = 1
Chart.defaults.plugins.tooltip.displayColors = false
Chart.defaults.plugins.tooltip.mode = 'nearest'
Chart.defaults.plugins.tooltip.intersect = false
Chart.defaults.plugins.tooltip.position = 'nearest'
Chart.defaults.plugins.tooltip.caretSize = 0
Chart.defaults.plugins.tooltip.caretPadding = 20
Chart.defaults.plugins.tooltip.cornerRadius = 8
Chart.defaults.plugins.tooltip.padding = 8

// Function that generates a gradient for line charts
export const chartAreaGradient = (ctx, chartArea, colorStops) => {
  if (!ctx || !chartArea || !colorStops || colorStops.length === 0) {
    return 'transparent'
  }
  const gradient = ctx.createLinearGradient(
    0,
    chartArea.bottom,
    0,
    chartArea.top
  )
  colorStops.forEach(({ stop, color }) => {
    gradient.addColorStop(stop, color)
  })
  return gradient
}

// Register Chart.js plugin to add a bg option for chart area
Chart.register({
  id: 'chartAreaPlugin',
  // eslint-disable-next-line object-shorthand
  beforeDraw: (chart) => {
    if (
      chart.config.options.chartArea &&
      chart.config.options.chartArea.backgroundColor
    ) {
      const ctx = chart.canvas.getContext('2d')
      const { chartArea } = chart
      ctx.save()
      ctx.fillStyle = chart.config.options.chartArea.backgroundColor
      // eslint-disable-next-line max-len
      ctx.fillRect(
        chartArea.left,
        chartArea.top,
        chartArea.right - chartArea.left,
        chartArea.bottom - chartArea.top
      )
      ctx.restore()
    }
  },
})

document.addEventListener('alpine:init', () => {
  Alpine.store('sidebar', {
      expanded: localStorage.getItem('sidebar-expanded') === 'true',
      toggle() {
          this.expanded = !this.expanded;
          localStorage.setItem('sidebar-expanded', this.expanded);
      }
  });
});

document.addEventListener('DOMContentLoaded', () => {
  // Light switcher
  const lightSwitches = document.querySelectorAll('.light-switch')
  if (lightSwitches.length > 0) {
    lightSwitches.forEach((lightSwitch, i) => {
      if (localStorage.getItem('dark-mode') === 'true') {
        lightSwitch.checked = true
      }
      lightSwitch.addEventListener('change', () => {
        const { checked } = lightSwitch
        lightSwitches.forEach((el, n) => {
          if (n !== i) {
            el.checked = checked
          }
        })
        document.documentElement.classList.add('[&_*]:!transition-none')
        if (lightSwitch.checked) {
          document.documentElement.classList.add('dark')
          document.querySelector('html').style.colorScheme = 'dark'
          localStorage.setItem('dark-mode', true)
          document.dispatchEvent(
            new CustomEvent('darkMode', { detail: { mode: 'on' } })
          )
        } else {
          document.documentElement.classList.remove('dark')
          document.querySelector('html').style.colorScheme = 'light'
          localStorage.setItem('dark-mode', false)
          document.dispatchEvent(
            new CustomEvent('darkMode', { detail: { mode: 'off' } })
          )
        }
        setTimeout(() => {
          document.documentElement.classList.remove('[&_*]:!transition-none')
        }, 1)
      })
    })
  }
  // Flatpickr
  flatpickr('.datepicker', {
    mode: 'range',
    static: true,
    monthSelectorType: 'static',
    dateFormat: 'M j, Y',
    defaultDate: [new Date().setDate(new Date().getDate() - 6), new Date()],
    prevArrow:
      '<svg class="fill-current" width="7" height="11" viewBox="0 0 7 11"><path d="M5.4 10.8l1.4-1.4-4-4 4-4L5.4 0 0 5.4z" /></svg>',
    nextArrow:
      '<svg class="fill-current" width="7" height="11" viewBox="0 0 7 11"><path d="M1.4 10.8L0 9.4l4-4-4-4L1.4 0l5.4 5.4z" /></svg>',
    onReady: (selectedDates, dateStr, instance) => {
      // eslint-disable-next-line no-param-reassign
      instance.element.value = dateStr.replace('to', '-')
      const customClass = instance.element.getAttribute('data-class')
      instance.calendarContainer.classList.add(customClass)
    },
    onChange: (selectedDates, dateStr, instance) => {
      // eslint-disable-next-line no-param-reassign
      instance.element.value = dateStr.replace('to', '-')
    },
  })

  // Display success message if present
  if (window.Laravel && window.Laravel.success) {
    Swal.fire({
      icon: 'success',
      title: window.Laravel.success.title,
      text: window.Laravel.success.text,
    })
  }

  // Display error message if present
  if (window.Laravel && window.Laravel.error) {
    Swal.fire({
      icon: 'error',
      title: window.Laravel.error.title,
      text: window.Laravel.error.text,
    })
  }
  if (window.Laravel.error) {
    // handle error display
    console.log(window.Laravel.error)
  }

  if (window.Laravel.success) {
    // handle success display
    console.log(window.Laravel.success)
  }
})
