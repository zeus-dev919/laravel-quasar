import Styled from 'vue3-styled-components';
const props = [];

const CardWidget = Styled('figure', props)`
display: flex;
margin: 0;
position: relative;
h2,
p {
  margin: 0;
}
figcaption {
  .more {
	position: absolute;
	top: 0px;
	left: 0;
	a {
	  color: #888;
	}
  }
  h2 {
	font-size: 20px;
	font-weight: 600;
  }
  p {
	font-size: 14px;
	color: #9299B8;
  }
}
`;

const CardWidgetIcon = Styled.div`
  width: 60px;
  height: 60px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #5F63F2;
  margin-right: 20px;
`;



export { CardWidget, CardWidgetIcon };