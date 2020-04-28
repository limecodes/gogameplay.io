import React, { Component } from 'react';
import ReactDOM from 'react-dom';

import { connect } from 'react-redux';

import { validateSearch } from '../actions/validation';

class SearchCoupon extends Component {
	
	handleSearchCoupon() {
		this.props.validateSearch();
	}

	render() {
		const CarrierError = () => {
			return (
				<small>You need to be on a cellular connection to verify carrier</small>
			);
		};

		if ( (this.props.validation.platform) && (this.props.validation.carrier) ) {
			return (
				<div style={{ marginTop: '1rem', textAlign: 'center' }}>
					<button className="btn btn-success" style={{ width: '100%' }} onClick={ this.handleSearchCoupon.bind(this) }>Search for coupon ></button>
				</div>
			);
		} else {
			return (
				<div style={{ marginTop: '1rem', textAlign: 'center' }}>
					<button className="btn btn-outline-danger" style={{ width: '100%' }}>Search for coupon</button>
					{(!this.props.visitor.carrier) ? <CarrierError /> : <div></div>}
				</div>
			);
		}
	}
}

const mapStateToProps = state => ({
	visitor: state.visitor,
	validation: state.validation
});

export default connect(mapStateToProps, { validateSearch })(SearchCoupon);