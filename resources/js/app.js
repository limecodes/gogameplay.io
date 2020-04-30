import React, { Component } from 'react';
import ReactDOM from 'react-dom';

import { Provider } from 'react-redux';
import { PersistGate } from 'redux-persist/integration/react';

import { store, persistor } from './store';

import { library } from '@fortawesome/fontawesome-svg-core';
import { faCheck, faAngleRight } from '@fortawesome/free-solid-svg-icons';

import RootComponent from './components/RootComponent';

library.add(faCheck, faAngleRight);

export default class App extends Component {

	constructor(props) {
		super(props);

		//TODO: Maybe instead of the server doing device detection, do it here
	}

	render() {
		return (
			<Provider store={ store }>
				<PersistGate persistor={ persistor }>
	        		<RootComponent device={ this.props.device } />
	        	</PersistGate>
	        </Provider>
    	);
	}
}

if (document.getElementById('app')) {
	// THIS IS FOR TESTING ONLY!!!
	if (navigator.connection) {
		NetworkInformation.prototype.type = 'wifi';
	}

	var elem = document.getElementById('app');
	var device = elem.getAttribute('data-device');

    ReactDOM.render(<App device={ device } />, elem);
}
