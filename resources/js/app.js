import React, { Component } from 'react';
import ReactDOM from 'react-dom';

import RootComponent from './components/RootComponent';

export default class App extends Component {

	render() {

		return (
	        <RootComponent />
    	);
	}
}

if (document.getElementById('app')) {
	var elem = document.getElementById('app');
	var uid = elem.getAttribute('data-uid');
	var device = elem.getAttribute('data-device');
	var connection = elem.getAttribute('data-connection');

    ReactDOM.render(<App uid={ uid } device={ device } connection={ connection } />, elem);
}
