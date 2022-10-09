const primaryColor = '#5F63F2';
const primaryHover = '#4347D9';
const secondaryColor = '#FF69A5';
const secondaryHover = '#E34A87';
const linkColor = '#1890ff';
const linkHover = '#0D79DF';
const headingColor = 'rgba(0, 0, 0, 0.85)';
const successColor = '#20C997';
const successHover = '#0CAB7C';
const warningColor = '#FA8B0C';
const warningHover = '#D47407';
const errorColor = '#f5222d';
const errorHover = '#E30D0F';
const infoColor = '#2C99FF';
const infoHover = '#0D79DF';
const darkColor = '#272B41';
const darkHover = '#131623';
const grayColor = '#5A5F7D';
const grayHover = '#363A51';
const lightColor = '#9299B8';
const lightHover = '#e2e6ea';
const whiteColor = '#ffffff';
const dashColor = '#5A5F7D';
const whiteHover = '#5A5F7D';
const extraLightColor = '#ADB4D2';
const dangerColor = '#FF4D4F';
const dangerHover = '#E30D0F';
const borderColorLight = '#F1F2F6';
const borderColorNormal = '#E3E6EF';
const borderColorDeep = '#C6D0DC';
const bgGrayColorDeep = '#EFF0F3';
const bgGrayColorLight = '#F8F9FB';
const bgGrayColorNormal = '#F4F5F7';
const lightGrayColor = '#868EAE';
const sliderRailColor = 'rgba(95,99,242,0.2)';
const graySolid = '#9299b8';
const pinkColor = '#F63178';
const btnlg = '48px';
const btnsm = '36px';
const btnxs = '29px';

const theme = {
	'primary-color': primaryColor, // primary color for all components
	'primary-hover': primaryHover, // primary color for all components
	'secondary-color': secondaryColor, // secondary color for all components
	'secondary-hover': secondaryHover, // secondary color for all components
	'link-color': linkColor, // link color
	'link-hover': linkHover, // link color
	'success-color': successColor, // success state color
	'success-hover': successHover, // success state color
	'warning-color': warningColor, // warning state color
	'warning-hover': warningHover, // warning state color
	'error-color': errorColor, // error state color
	'error-hover': errorHover, // error state color
	'info-color': infoColor, // info state color
	'info-hover': infoHover, // info state color
	'dark-color': darkColor, // info state color
	'dark-hover': darkHover, // info state color
	'gray-color': grayColor, // info state color
	'gray-hover': grayHover, // info state color
	'light-color': lightColor, // info state color
	'light-hover': lightHover, // info state color
	'white-color': whiteColor, // info state color
	'white-hover': whiteHover, // info state color
	white: whiteColor,
	black: '#000',
	pink: pinkColor,
	'dash-color': dashColor, // info state color
	'dash-hover': grayHover, // info state color
	'extra-light-color': extraLightColor, // info state color
	'danger-color': dangerColor,
	'danger-hover': dangerHover,
	'font-family': "'Inter', sans-serif",
	'font-size-base': '14px', // major text font size
	'heading-color': headingColor, // heading text color
	'text-color': darkColor, // major text color
	'text-color-secondary': grayColor, // secondary text color
	'disabled-color': 'rgba(0, 0, 0, 0.25)', // disable state color
	'border-radius-base': '4px', // major border radius
	'border-color-base': '#d9d9d9', // major border color
	'box-shadow-base': '0 2px 8px rgba(0, 0, 0, 0.15)', // major shadow for layers
	'border-color-light': borderColorLight,
	'border-color-normal': borderColorNormal,
	'border-color-deep': borderColorDeep,
	'bg-color-light': bgGrayColorLight,
	'bg-color-normal': bgGrayColorNormal,
	'bg-color-deep': bgGrayColorDeep,
	'light-gray-color': lightGrayColor,
	'gray-solid': graySolid,
	'btn-height-large': btnlg,
	'btn-height-small': btnsm,
	'btn-height-extra-small': btnxs,
	'btn-default-color': darkColor,

	// cards
	'card-head-background': '#ffffff',
	'card-head-color': darkColor,
	'card-background': '#ffffff',
	'card-head-padding': '16px',
	'card-padding-base': '12px',
	'card-radius': '10px',
	'card-shadow': '0 5px 20px rgba(146,153,184,0.03)',

	// Layout
	'layout-body-background': '#F4F5F7',
	'layout-header-background': '#ffffff',
	'layout-footer-background': '#fafafa',
	'layout-header-height': '64px',
	'layout-header-padding': '0 15px',
	'layout-footer-padding': '24px 15px',
	'layout-sider-background': '#ffffff',
	'layout-trigger-height': '48px',
	'layout-trigger-background': '#002140',
	'layout-trigger-color': '#fff',
	'layout-zero-trigger-width': '36px',
	'layout-zero-trigger-height': '42px',
	// Layout light theme
	'layout-sider-background-light': '#fff',
	'layout-trigger-background-light': '#fff',
	'layout-trigger-color-light': 'rgba(0, 0, 0, 0.65)',

	// PageHeader
	// ---
	'page-header-padding': '24px',
	'page-header-padding-vertical': '16px',
	'page-header-padding-breadcrumb': '12px',
	'page-header-back-color': '#000',
	'page-header-ghost-bg': 'inherit',

	// Popover body background color
	'popover-color': darkColor,

	// alert
	'alert-success-border-color': successColor,
	'alert-success-bg-color': successColor + 15,
	'alert-error-bg-color': errorColor + 15,
	'alert-warning-bg-color': warningColor + 15,
	'alert-info-bg-color': infoColor + 15,

	// radio btn
	'radio-button-checked-bg': primaryColor,

	// gutter width
	'grid-gutter-width': 25,

	// skeleton
	'skeleton-color': borderColorLight,

	// slider
	'slider-rail-background-color': sliderRailColor,
	'slider-rail-background-color-hover': sliderRailColor,
	'slider-track-background-color': primaryColor,
	'slider-track-background-color-hover': primaryColor,
	'slider-handle-color': primaryColor,
	'slider-handle-size': '16px',

	// input
	'input-height-base': '48px',
	'input-border-color': borderColorNormal,
	'input-height-sm': '30px',
	'input-height-lg': '50px',

	// rate
	'rate-star-color': warningColor,
	'rate-star-size': '13px',

	// Switch
	'switch-min-width': '30px',
	'switch-sm-min-width': '30px',
	'switch-height': '18px',
	'switch-sm-height': '15px',

	// result
	'result-title-font-size': '20px',
	'result-subtitle-font-size': '12px',
	'result-icon-font-size': '50px',

	// tabs
	'tabs-horizontal-padding': '12px 15px',
	'tabs-horizontal-margin': '0',

	// list
	'list-item-padding': '10px 24px',

	// Tags
	'tag-default-bg': '#EFF0F3',
	'tag-default-color': darkColor,
	'tag-font-size': '11px',
};

const darkTheme = {
	...theme,
	'primary-color': 'red',
	backgroundColor: '#000',
};

export { theme, darkTheme };