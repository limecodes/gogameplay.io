import React, { Component } from 'react';
import ReactDOM from 'react-dom';

import { Provider } from 'react-redux';

import { store } from './store';

import RootComponent from './components/RootComponent';

export default class App extends Component {

	render() {
		return (
			<Provider store={ store }>
	        	<RootComponent uid={ this.props.uid } device={ this.props.device } connection={ this.props.connection } />
	        </Provider>
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
