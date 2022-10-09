import Styled from 'vue3-styled-components';
const props = ['innerWidth', 'collapsed', 'cssSettings'];

const Div = Styled('div', props)`

`;

const MainArea = Styled('div', props)`
	margin-left: ${({ innerWidth, collapsed }) => innerWidth <= 991 ? '2px' : (collapsed ? '80px' : '240px')};
	min-height: 100vh;
`;

const HeaderRightIcons = Styled('div', props)`
	display: flex;
	justify-content: flex-end;
	align-items: center;
`;

const MainHeader = Styled('div', props)`
	.ant-layout-header, .ant-menu-horizontal{
		background: #2e3f50 !important;
	}
`;

const MainContentArea = Styled('div', props)`

	.breadcrumb-header {
		.page-content-sub-header {
			padding-top: 10px !important;
			padding-bottom: 10px !important;

			${({ cssSettings }) => cssSettings && cssSettings.headerMenuMode == 'horizontal' ? `border-bottom: 0px` : ''};
		}

		.breadcrumb-left-border {
			border-left: none !important;
		}

		.ant-card-body {
			padding: ${({ cssSettings }) => cssSettings && cssSettings.headerMenuMode == 'horizontal' ? `0px ${cssSettings.leftRightPadding}` : '0px'};
			margin: ${({ cssSettings }) => cssSettings && cssSettings.headerMenuMode == 'horizontal' ? '0px' : '0px 16px 0'};
		}
	}

	.dashboard-page-content-container {
		padding: ${({ cssSettings }) => cssSettings && cssSettings.headerMenuMode == 'horizontal' ? `0px ${cssSettings.leftRightPadding}` : '0px'};
		margin: ${({ cssSettings }) => cssSettings && cssSettings.headerMenuMode == 'horizontal' ? '0px' : '0px 16px 0'};
		${({ cssSettings }) => cssSettings && cssSettings.headerMenuMode == 'horizontal' ? `border-top: 1px solid #cbd6e2` : ''};
	}

	.page-content-container {
		min-height: calc(100vh - 165px);
	}

	.email-page-content-container {
		min-height: calc(100vh - 202px);
	}

	.page-content-container, .email-page-content-container {
		${({ cssSettings }) => cssSettings && cssSettings.headerMenuMode == 'horizontal' ? `border-top: 1px solid #cbd6e2` : ''};
		
		.ant-card-body {
			padding: ${({ cssSettings }) => cssSettings && cssSettings.headerMenuMode == 'horizontal' ? `0px ${cssSettings.leftRightPadding}` : '0px'};
			margin: ${({ cssSettings }) => cssSettings && cssSettings.headerMenuMode == 'horizontal' ? '0px' : '0px 16px 0'};
			padding-top: 30px;
		}
	}

	.setting-sidebar {
		margin-left: ${({ cssSettings }) => cssSettings && cssSettings.headerMenuMode == 'horizontal' ? `${cssSettings.leftRightPadding}` : '0px'};
	}
`;

export { Div, MainArea, HeaderRightIcons, MainContentArea, MainHeader };